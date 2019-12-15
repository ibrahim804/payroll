<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Payment extends Model
{
    //

    protected $fillable = [
        'user_id',
        'employee_monthly_cost',
        'payable_amount',
        'payment_date',
        'month',
        'year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
