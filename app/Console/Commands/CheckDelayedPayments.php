<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CheckDelayedPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-delayed-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {   

        //get all loan application
        $all_loan = db::select("SELECT la.* from loan_application la where la.loan_status = 5 and la.status = 1");

        foreach ($all_loan as $key => $value) {
            //check all delayed tenure per loan
            $loan_tenure = db::select("SELECT 
                                lt.id,
                                DATE(lt.date) AS tenure_date,
                                (
                                    IF(MAX(lt.payment_status_id) = 1, MAX(lt.principal), 0) +
                                    IF(MAX(lti.payment_status_id) = 1, MAX(lti.interest), 0) +
                                    IFNULL(SUM(IF(ltp.payment_status_id = 1, ltp.penalty, 0)), 0)
                                ) AS total_all
                            FROM loan_tenure lt
                            LEFT JOIN loan_tenure_interest lti 
                                ON lti.tenure_id = lt.id
                            LEFT JOIN loan_tenure_penalty ltp 
                                ON ltp.tenure_id = lt.id
                            WHERE lt.loan_id = ?
                            AND DATE_FORMAT(lt.date, '%Y-%m') <= DATE_FORMAT(NOW(), '%Y-%m')

                            GROUP BY lt.id, lt.date
                            HAVING total_all > 0;

                        ",[$value->id]);

            foreach ($loan_tenure as $k => $v) {
                //check how many penalty tenure has
                $penalty_count = DB::table('loan_tenure_penalty')
                    ->where('tenure_id', $v->id)
                    ->count();

                // for loan penalty computation
                $dueDate = Carbon::parse($v->tenure_date);
                $today   = Carbon::today();

                // How many full weeks late
                $weeksDelayed = intval($dueDate->diffInWeeks($today, false));
                // dd($);
                if ($weeksDelayed > 0 && $weeksDelayed > $penalty_count) {

                    $to_add = $weeksDelayed - $penalty_count; 

                    for ($i = 0; $i < $to_add; $i++) { 
                        DB::table('loan_tenure_penalty')->insert([
                            'tenure_id' => $v->id,
                            'penalty' => 50,
                            'payment_status_id' => 1,
                            'created_at' => now(),
                            'date' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                echo $weeksDelayed;


            }


        }
    }
}
