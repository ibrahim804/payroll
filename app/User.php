<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Attendance;
use App\Department;
use App\Designation;
use App\Leave;
use App\Salary;
use App\Working_day;
use App\LeaveCount;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    private $admin_id = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'full_name',
        'user_name',
        'email',
        'password',
        'date_of_birth',
        'fathers_name',
        'gender',
        'marital_status',
        'nationality',
        'permanent_address',
        'present_address',
        'passport_number',
        'photo_path',
        'phone',
        'designation_id',
        'department_id',
        'salary_id',
        'working_day_id',
        'joining_date',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'photo_path', 'created_at', 'updated_at', 'deleted_at',
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin($user_id)
    {
        return ($user_id == $this->admin_id) ? 'true' : 'false';
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    public function working_day()
    {
        return $this->belongsTo(Working_day::class);
    }

    public function leave_counts()
    {
        return $this->hasMany(LeaveCount::class);
    }
}

















//
