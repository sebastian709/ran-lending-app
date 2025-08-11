<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLoanRequestAccess extends Model
{
    protected $table = 'admin_loan_request_access';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}