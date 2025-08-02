<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_payment_status extends Model
{
    protected $table = 'loan_payment_statuses';

    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'tenure_id',
    //     'interest',
    // ];
}
