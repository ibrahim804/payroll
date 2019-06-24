<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Leave_category;
use App\User;

class Leave extends Model
{
    // const CREATED_AT = 'application_date';

    protected $fillable = [
        'user_id',
        'leave_category_id',
        'leave_description',
        'application_date',
        'month',
        'start_date',
        'end_date',
        'approval_status',
    ];

    public function leave_category()
    {
        return $this->belongsTo(Leave_category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
