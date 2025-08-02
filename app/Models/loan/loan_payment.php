<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_payment extends Model
{
    protected $table = 'loan_payments';

    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'tenure_id',
    //     'interest',
    // ];
}
