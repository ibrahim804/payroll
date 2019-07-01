<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Query will be enough bigger, so no need to make any relation with user and leave category.

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
}
