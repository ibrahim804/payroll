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

    // public function index()
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view all leave counts');
    //
    //     $this->renewLeaveCountAll();
    //
    //     $leave_counts = LeaveCount::all();
    //
    //     $i = 0; $infos = [];
    //
    //     foreach($leave_counts as $leave_count) {
    //
    //         $infos[$i] = new LeaveCount;
    //
    //         $infos[$i]->id = $leave_count->id;
    //         $infos[$i]->full_name = $leave_count->user->full_name;
    //         $infos[$i]->leave_type = $leave_count->leave_category->leave_type;
    //         $infos[$i]->leave_left = $leave_count->leave_left;
    //         $infos[$i]->leave_count_start = $leave_count->leave_count_start;
    //         $infos[$i]->leave_count_expired = $leave_count->leave_count_expired;
    //
    //         $i++;
    //     }
    //
    //     return $infos;
    // }

    public function store($user_id, $joining_date)      // CALLED BY REDIRECT FROM register METHOD IN UserController (AFTER USER REGISTRATION), NOT BY ROUTE.
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create any leave count');

        $leave_categories = Leave_category::all();
        $hasAny = $leave_categories->count();

        if(! $hasAny) return $this->getErrorMessage('No Leave category found.');

        $user = User::find($user_id);

        if($user->leave_counts->count() > 0) return $this->getErrorMessage('Already exists');

        $validate_attributes = [];

        foreach ($leave_categories as $leave_category) {
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
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create leave count');

        $leave_category = Leave_category::find($leave_category_id);
        $all_users = User::all();
        $validate_attributes = [];

        foreach ($all_users as $user) {
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

    // public function show($user_id, $leave_category_id)
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false' and auth()->id() != $user_id)
    //     {
    //         return $this->getErrorMessage('You don\'t have permission to check others leave count');
    //     }
    //
    //     $this->renewLeaveCountAll();
    //
    //     $leave_count = LeaveCount::where([
    //         ['user_id', $user_id],
    //         ['leave_category_id', $leave_category_id],
    //     ])->first();
    //
    //     if(! $leave_count) return $this->getErrorMessage('This user has no leave count with this leave category.');
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'leave_left' => $leave_count->leave_left,
    //             'leave_count_start' => $leave_count->leave_count_start,
    //             'leave_count_expired' => $leave_count->leave_count_expired,
    //         ]
    //     ];
    // }

    // public function update($id)
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update any leave count');
    //
    //     $this->renewLeaveCountAll();
    //
    //     $leave_count = LeaveCount::findOrFail($id);
    //     $validate_attributes = request()->validate(['leave_left' => 'string']);
    //     $leave_count->update($validate_attributes);
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'leave_left' => $leave_count->leave_left,
    //         ]
    //     ];
    // }

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

    // private function renewLeaveCountAll()
    // {
    //     $leave_counts = LeaveCount::all();
    //
    //     foreach ($leave_counts as $leave_count) {
    //         $this->renewLeaveCountAnEmployee($leave_count);
    //     }
    // }

    // private function renewLeaveCountAnEmployee($leave_count)
    // {
    //     $expired_seconds = strtotime($leave_count->leave_count_expired);
    //     $today_seconds = strtotime(date('Y-m-d'));
    //
    //     if($today_seconds < $expired_seconds) return;
    //
    //     $validate_attributes = [];
    //     $validate_attributes['leave_count_start'] = $leave_count->leave_count_expired;
    //     $new_date_seconds = strtotime('+ 1 year', $expired_seconds); // second param should be in seconds
    //     $validate_attributes['leave_count_expired'] = date('Y-m-d', $new_date_seconds);
    //
    //     if($leave_count->leave_category_id == '1')
    //     {
    //         $validate_attributes['leave_left'] = $this->myObject->casual_gift;
    //         $leave_count = $leave_count->update($validate_attributes);
    //     }
    //     else if($leave_count->leave_category_id == '2')
    //     {
    //         $validate_attributes['leave_left'] = $this->myObject->sick_gift;
    //         $leave_count = $leave_count->update($validate_attributes);
    //     }
    // }

}










//
