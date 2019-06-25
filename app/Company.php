<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Working_day;

class Company extends Model
{
    //

    protected $fillable = [
        'name',
        'email',
        'address',
        'country',
        'phone',
        'mobile',
        'website',
        'working_day_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function working_day()
    {
        return $this->belongsTo(Working_day::class);
    }
}
