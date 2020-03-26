<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LoanHistory extends Model
{
    protected $fillable = [
        'user_id', 'month', 'year',
        'month_count', 'contract_duration',
        'actual_loan_amount', 'current_loan_amount', 'paid_this_month', 'total_paid_amount',
        'loan_status'
    ];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
