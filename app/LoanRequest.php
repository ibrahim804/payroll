<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LoanRequest extends Model
{
    protected $fillable = ['user_id', 'application_date', 'provident_fund', 'requested_amount', 'approval_status'];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
