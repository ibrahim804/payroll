<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Leave_category;

class Leave extends Model
{
    //

    protected $fillable = [
        //'user_id',
        'leave_category_id',
        'leave_description',
        'application_date',
        'start_date',
        'end_date',
        'approval_status',
    ];

    public function leave_category()
    {
        return $this->belongsTo(Leave_category::class);
    }
}
