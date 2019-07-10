<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Working_day;
use App\User;

class Company extends Model
{
    use SoftDeletes;

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

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
