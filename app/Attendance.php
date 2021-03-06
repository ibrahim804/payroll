<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Attendance extends Model
{
    //

    protected $fillable = [
        'user_id',
        'date',
        'day',
        'month',
        'entry_time',
        'exit_time',
        'updatable_flag',
    ];

    protected $hidden = [
        'day', 'month', 'created_at', 'updated_at', 'updatable_flag',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
