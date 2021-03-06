<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Leave_category;

class LeaveCount extends Model
{
    protected $fillable = [
        'user_id',
        'leave_category_id',
        'leave_left',
        'leave_count_start',
        'leave_count_expired',
        'times_already_taken',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leave_category()
    {
        return $this->belongsTo(Leave_category::class);
    }
}
