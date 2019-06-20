<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Leave;

class Leave_category extends Model
{
    use SoftDeletes;

    protected $fillable = ['leave_type'];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
