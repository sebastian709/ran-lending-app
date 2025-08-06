<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_tenure_shortpayment extends Model
{
    protected $table = 'loan_tenure_shortpayments';

    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'tenure_id',
    //     'interest',
    // ];
}
