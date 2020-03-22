<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProvidentFund extends Model
{
    protected $fillable = [
        'user_id', 'month', 'year',
        'opening_balance',
        'gross_salary', 'deposit_rate', 'deposit_balance', 'opening_and_deposit',
        'payment_in_times', 'company_contribution_rate', 'company_contribution',
        'closing_balance'
    ];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
