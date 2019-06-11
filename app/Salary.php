<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    //

    protected $fillable = [
        'basic_salary',
        'house_rent_allowance',
        'medical_allowance',
        'special_allowance',
        'fuel_allowance',
        'phone_bill_allowance',
        'other_allowance',
        'tax_deduction',
        'provident_fund',
        'other_deduction',
    ];
}
