<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_application extends Model
{
    protected $table = 'loan_application';

    protected $primaryKey = 'id';

    protected $fillable = [
        'loan_applicant',
        'loan_status',
        'loan_amount',
        'loan_tenure',
        'interest_rate',
        'total_amount',
        'status',
    ];

    public $timestamps = true;
}
