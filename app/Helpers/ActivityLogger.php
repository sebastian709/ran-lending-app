<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityLogger
{
    public static function log($action, $description, $loanId = null)
    {
        DB::table('activity_logs')->insert([
            'user_id'     => Auth::id(),
            'loan_id'     => $loanId,
            'action'      => $action,
            'description' => $description,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
