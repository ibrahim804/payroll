<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
