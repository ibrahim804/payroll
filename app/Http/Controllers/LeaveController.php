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
        $validator = $this->validateLeave($request);

        if($validator->fails()) return $this->getErrorMessage($validator->errors());

        $inputs = $request->all();
        $inputs['user_id'] = auth()->id();
        $inputs['application_date'] = date("Y-m-d H:i:s", strtotime('+6 hours'));
        $inputs['month'] = date("M", strtotime('+6 hours'));
        $leave = Leave::create($inputs);

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

    private function validateLeave(Request $request)
    {
        return Validator::make($request->all(), [
            'leave_category_id' => 'required|string',
            'leave_description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
    }
}

















//
