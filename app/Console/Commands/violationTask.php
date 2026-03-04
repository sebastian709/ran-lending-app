<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class violationTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:violation-task';

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
                            -- HAVING total_all > 0
                            limit 1

                        ",[$value->id]);

            foreach ($loan_tenure as $k => $v) {

                // For loan penalty computation
                $dueDate = Carbon::parse($v->tenure_date);
                $today   = Carbon::today();

                // How many full months late
                $monthsDelayed = intval($dueDate->diffInMonths($today, false));

                // Convert to penalties per 3 months
                $penaltiesDue = intdiv($monthsDelayed, 3); // integer division
                // dd($penaltiesDue,$penalty_count,$value->red_flag);
                // 🔥 Only compute penalty if LATE and more than already charged
                if ($penaltiesDue > 0 && (int)$value->red_flag === 0) {

                    DB::table('loan_application')
                    ->where('id', $value->id)
                    ->update(['red_flag' => 1]);

                echo "Months late: $monthsDelayed, Penalties due: $penaltiesDue\n";
                }else{
                echo "Already Red Flag";
                
                }

            }



        }
    }
}
