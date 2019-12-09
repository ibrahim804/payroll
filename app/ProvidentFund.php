<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProvidentFund extends Model
{
    protected $fillable = ['user_id'];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
