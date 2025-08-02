<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_tenure_interest extends Model
{
    protected $table = 'loan_tenure_interest';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tenure_id',
        'interest',
    ];

    public $timestamps = true;
}
