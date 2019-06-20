<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Leave;

class Leave_category extends Model
{
    //

    protected $fillable = ['leave_type'];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
