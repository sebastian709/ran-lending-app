<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_payment extends Model
{
    protected $table = 'loan_payments';
    protected $primaryKey = 'id';
    public $timestamps = true; 

    protected $fillable = [
        'amount_sent',
        'payment_status_id',
        'reference_code',
        'payment',
        'sent_to',
        'remarks',
        'attachment',
        'added_by',
        'created_at',
        'updated_at',
    ];
}
