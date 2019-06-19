<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Department;

class Designation extends Model
{
    //

    protected $fillable = ['designation'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
