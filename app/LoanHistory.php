<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LoanHistory extends Model
{
    protected $fillable = ['user_id', 'actual_loan_amount', 'paid_amount'];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
