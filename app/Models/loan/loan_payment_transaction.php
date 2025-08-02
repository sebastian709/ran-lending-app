<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_payment_transaction extends Model
{
    protected $table = 'loan_payment_transactions';

    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'tenure_id',
    //     'interest',
    // ];
}
