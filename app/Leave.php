<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Leave_category;
use App\User;

class Leave extends Model
{
    protected $fillable = [
        'user_id',
        'leave_category_id',
        'leave_description',
        'month',
        'start_date',
        'end_date',
        'approval_status',
    ];

    //const CREATED_AT = 'application_date';

    public function leave_category()
    {
        return $this->belongsTo(Leave_category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
