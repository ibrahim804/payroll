<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Salary extends Model
{
    //

    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
