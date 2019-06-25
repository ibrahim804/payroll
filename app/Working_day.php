<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Working_day extends Model
{
    protected $fillable = [
        'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
