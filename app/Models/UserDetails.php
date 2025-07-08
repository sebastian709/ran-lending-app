<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $fillable = [
        'user_id',
        'house_no',
        'street',
        'barangay',
        'city',
        'province',
    ];
}
