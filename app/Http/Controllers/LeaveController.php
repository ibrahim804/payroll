<?php

namespace App\Http\Controllers;

use App\Leave;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use Validator;

class LeaveController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index($month)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view all leaves');

        $leaves = Leave::where('month', $month)->get();

        return
        [
            [
                'status' => 'OK',
                'leaves' => $leaves,
            ]
        ];
    }

    public function store(Request $request)
    {
        $validate_attributes = $this->validateLeave();

        $validate_attributes['user_id'] = auth()->id();
        $validate_attributes['application_date'] = date("Y-m-d H:i:s", strtotime('+6 hours'));
        $validate_attributes['month'] = date("M", strtotime('+6 hours'));

        $leave = Leave::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'leave' => $leave,
            ]
        ];
    }

    public function show($user_id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view leave');

        $leaves = User::findOrFail($user_id)->leaves;

        return
        [
            [
                'status' => 'OK',
                'leave' => $leaves,
            ]
        ];
    }

    public function update($id) // check end_date >= start_date later
    {
        $leave = Leave::findOrFail($id);

        if($leave->user->id != auth()->id()) return $this->getErrorMessage('You can\'t update others leave information.');

        $validate_attributes = request()->validate([
            'leave_category_id' => 'string', 'leave_description' => 'string', 'start_date' => 'date', 'end_date' => 'date',
        ]);

        $leave->update($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'user_id' => $leave->user->id,
                'leave_type' => $leave->leave_category->leave_type,
                'description' => $leave->leave_description,
                'application_date' => $leave->application_date,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
            ]
        ];
    }

    private function validateLeave()
    {
        return request()->validate([
            'leave_category_id' => 'required|string',
            'leave_description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
    }
}

















//
