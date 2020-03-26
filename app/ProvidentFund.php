<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProvidentFund extends Model
{
    protected $fillable = [
        'user_id', 'month', 'year',
        'opening_balance', 'basic_salary', 'deposit_rate', 'deposit_balance', 'pf_yearly_rate', 'closing_balance'
    ];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
