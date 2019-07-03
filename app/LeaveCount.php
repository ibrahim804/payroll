<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LeaveCount extends Model
{
    protected $fillable = [
        'user_id',
        'leave_category_id',
        'leave_left',
        'leave_count_start',
        'leave_count_expired',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
