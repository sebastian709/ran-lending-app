<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_tenure extends Model
{
    protected $table = 'loan_tenure';

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'loan_id',
        'principal',
        'date',
    ];

    public $timestamps = true;
}
