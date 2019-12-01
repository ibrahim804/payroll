<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProvidentFund extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'month_count',
        'opening_balance',
        'deposit_amount_on_first_fifteen_days',
        'deposit_date_on_first_fifteen_days',
        'deposit_amount_on_second_fifteen_days',
        'deposit_date_on_second_fifteen_days',
        'withdraw_amount_on_first_fifteen_days',
        'withdraw_date_on_first_fifteen_days',
        'withdraw_amount_on_second_fifteen_days',
        'withdraw_date_on_second_fifteen_days',
        'lowest_balance',
        'rate',
        'interest_for_this_month',
        'closing_balance',
    ];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
