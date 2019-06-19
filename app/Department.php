<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Designation;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'department_name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }
}
