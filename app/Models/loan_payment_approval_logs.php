<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class loan_payment_approval_logs extends Model
{
    protected $table = 'loan_payment_approval_logs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'loan_payment_id',
        'reason',
        'remarks',
        'instruction',
        'actual_amount',
        'attachment',
        'status',
        'action',
    ];
}
