<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIncome extends Model
{
    protected $fillable = [
        'user_id',
        'occupation',
        'income',
        'employment_status',
        'specified_others'
    ];
}
