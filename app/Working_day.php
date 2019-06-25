<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Working_day extends Model
{
    protected $fillable = [
        'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
