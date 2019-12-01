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
use App\Company;
use App\ProvidentFund;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    private $admin_id = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [                                         // fillable fields can be stored manually
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
        'company_id',
        'designation_id',
        'department_id',
        'salary_id',
        'working_day_id',
        'joining_date',
        'verification_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [                                           // never show these fields in json response
        'created_at', 'updated_at', 'deleted_at',
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

    public function isAdmin($user_id)                               // this method returns whether the user is admin or not
    {
        return ($user_id == $this->admin_id) ? 'true' : 'false';
    }

    public function attendances()                                   // returns all attendances of a user. one(user) to many(attendances) relationship.
    {
        return $this->hasMany(Attendance::class);
    }

    public function department()                                    // returns department of a user. many(users) to one(department) relationship.
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()                                   // returns designation of a user. many(users) to one(designation) relationship.
    {
        return $this->belongsTo(Designation::class);
    }

    public function leaves()                                        // returns all leaves of a user. one(user) to many(leaves) relationship.
    {
        return $this->hasMany(Leave::class);
    }

    public function salary()                                        // returns salary of a user. one(user) to one(salary) relationship.
    {
        return $this->hasOne(Salary::class);
    }

    public function working_day()                                   // returns working_day of a user. many(users) to one(working_day) relationship.
    {
        return $this->belongsTo(Working_day::class);
    }

    public function leave_counts()                                  // returns all leave_counts of a user. one(user) to many(leave_counts) relationship.
    {
        return $this->hasMany(LeaveCount::class);
    }

    public function company()                                       // returns company of a user. many(user) to one(company) relationship.
    {
        return $this->belongsTo(Company::class);
    }

    public function provident_funds()
    {
        return $this->hasMany(ProvidentFund::class);
    }
}

















//
