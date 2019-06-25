<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //

    protected $fillable = [
        'name',
        'email',
        'address',
        'country',
        'phone',
        'mobile',
        'website',
        'working_days_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
