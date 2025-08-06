<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_payment_type extends Model
{
    protected $table = 'loan_payment_types';

    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'tenure_id',
    //     'interest',
    // ];
}
