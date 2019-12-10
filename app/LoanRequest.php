<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LoanRequest extends Model
{
    protected $fillable = ['user_id', 'requested_amount'];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
