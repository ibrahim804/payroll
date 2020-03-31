<?php

namespace App\Http\Controllers;

use App\LeaveCount;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\User;
use App\Leave_category;
use DateTime;
use App\MyErrorObject;

class LeaveCountController extends Controller
{
    use CustomsErrorsTrait;
    private $myObject;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->myObject = new MyErrorObject;
    }

    public function store($user_id)      // CALLED BY REDIRECT FROM register METHOD IN UserController (AFTER USER REGISTRATION), NOT BY ROUTE.
    {
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

        $leave_categories = Leave_category::all();
        $hasAny = $leave_categories->count();

        if(! $hasAny) return $this->getErrorMessage('No Leave category found.');

        $user = User::find($user_id);

        if($user->leave_counts->count() > 0) return $this->getErrorMessage('Already exists');

        $validate_attributes = [];

        foreach ($leave_categories as $leave_category) { // casual and sick and block and (paternity or maternity)

            if(
                ($user->gender == 'male' and $leave_category->leave_type == $this->myObject->gender_specialized_leave_categories[1]) or
                ($user->gender == 'female' and $leave_category->leave_type == $this->myObject->gender_specialized_leave_categories[0])
            ) {
                continue;
            }

            $validate_attributes['user_id'] = $user->id;
            $validate_attributes['leave_category_id'] = $leave_category->id;
            $validate_attributes['leave_left'] = $leave_category->default_limit;
            $validate_attributes['leave_count_start'] = $user->joining_date;
            $validate_attributes['leave_count_expired'] = new DateTime($validate_attributes['leave_count_start']);
            $validate_attributes['leave_count_expired'] = date_add($validate_attributes['leave_count_expired'], date_interval_create_from_date_string('1 year'));

            $leave_count = LeaveCount::create($validate_attributes);
        }

        return
        [
            [
                'status' => 'OK',
                'id' => $user_id,       // must be returned to create working days
                'message' => 'User created. Also, leave counts for this user of all categories created successfully',
            ]
        ];
    }

    public function createAfterNewLeaveCategoryCreation($leave_category_id)   // CALLED BY REDIRECT (AFTER NEW LEAVE CATEGORY CREATION), NOT BY ROUTE.
    {
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

        $leave_category = Leave_category::find($leave_category_id);
        $all_users = User::all();
        $validate_attributes = [];

        foreach ($all_users as $user) {

            if(
                ($user->gender == 'male' and $leave_category->leave_type == $this->myObject->gender_specialized_leave_categories[1]) or
                ($user->gender == 'female' and $leave_category->leave_type == $this->myObject->gender_specialized_leave_categories[0])
            ) {
                continue;
            }

            $validate_attributes['user_id'] = $user->id;
            $validate_attributes['leave_category_id'] = $leave_category->id;
            $validate_attributes['leave_left'] = $leave_category->default_limit;
            $validate_attributes['leave_count_start'] = $user->joining_date;
            $validate_attributes['leave_count_expired'] = new DateTime($validate_attributes['leave_count_start']);
            $validate_attributes['leave_count_expired'] = date_add($validate_attributes['leave_count_expired'], date_interval_create_from_date_string('1 year'));

            $leave_count = LeaveCount::create($validate_attributes);
        }

        return
        [
            [
                'status' => 'OK',
                'message' => 'leave Counts of this leave category created for all employees',
            ]
        ];
    }

    public function employeeIndex()
    {
        $leave_counts = auth()->user()->leave_counts;

        return
        [
            [
                'status' => 'OK',
                'leave_counts' => $leave_counts,
            ]
        ];
    }

}










//
