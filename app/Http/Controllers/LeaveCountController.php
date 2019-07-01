<?php

namespace App\Http\Controllers;

use App\LeaveCount;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\User;
use DateTime;

class LeaveCountController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create any leave count');

        $validate_attributes = $this->validateLeaveCount();

        $flag = LeaveCount::where([
            ['user_id', $validate_attributes['user_id']],
            ['leave_category_id', $validate_attributes['leave_category_id']],
        ])->count();

        if($flag) return $this->getErrorMessage('Already exist');

        $validate_attributes['leave_count_start'] = User::findOrFail($validate_attributes['user_id'])->joining_date;
        $validate_attributes['leave_count_expired'] = new DateTime($validate_attributes['leave_count_start']);
        $validate_attributes['leave_count_expired'] = date_add($validate_attributes['leave_count_expired'], date_interval_create_from_date_string('1 year'));

        $leave_count = LeaveCount::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'user_id' => $leave_count->user_id,
                'leave_category_id' => $leave_count->leave_category_id,
                'leave_left' => $leave_count->leave_left,
                'leave_count_start' => $leave_count->leave_count_start,
                'leave_count_expired' => $leave_count->leave_count_expired,
            ]
        ];
    }

    public function show(LeaveCount $leaveCount)
    {
        //
    }

    public function update(Request $request, LeaveCount $leaveCount)
    {
        //
    }

    public function destroy(LeaveCount $leaveCount)
    {
        //
    }

    private function validateLeaveCount()
    {
        return request()->validate ([
            'user_id' => 'required|string',
            'leave_category_id' => 'required|string',
            'leave_left' => 'required|string',
        ]);
    }

}










//
