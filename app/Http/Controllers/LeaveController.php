<?php

namespace App\Http\Controllers;

use App\Leave;
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

    public function myLeaves()
    {
        $myLeaves = auth()->user()->leaves;

        return
        [
            [
                'status' => 'OK',
                'myLeaves' => $myLeaves,
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

    public function show($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view leave');

        $leave = Leave::findOrFail($id);

        return
        [
            [
                'status' => 'OK',
                'leave' => $leave,
            ]
        ];
    }

    public function update(Request $request, Leave $leave)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create leave category');
    }

    public function destroy(Leave $leave)
    {
        //
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
