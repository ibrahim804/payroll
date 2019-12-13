<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LoanHistory extends Model
{
    protected $fillable = ['user_id', 'month', 'year', 'month_count',
        'actual_loan_amount', 'yearly_interest_rate', 'current_loan_amount', 'paid_amount', 'loan_status'];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
