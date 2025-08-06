<?php

namespace App\Models\loan;

use Illuminate\Database\Eloquent\Model;

class loan_payment_account extends Model
{
    protected $table = 'loan_payment_accounts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'account_name',
        'account_number',
        'image',
        'status',
    ];
}
