<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_tenure_penalty extends Model
{
    protected $table = 'loan_tenure_penalty';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tenure_id',
        'penalty',
    ];

    public $timestamps = true;
}
