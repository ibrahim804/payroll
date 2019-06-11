<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //

    protected $fillable = [
        'user_id',
        'date',
        'month',
        'entry_time',
        'exit_time',
    ];
}
