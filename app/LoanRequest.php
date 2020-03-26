<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LoanRequest extends Model
{
    protected $fillable = ['user_id', 'application_date', 'available_amount', 'requested_amount', 'contract_duration', 'approval_status'];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
