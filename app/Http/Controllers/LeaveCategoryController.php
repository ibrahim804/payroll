<?php

namespace App\Http\Controllers;

use App\Leave_category;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use Validator;

class LeaveCategoryController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        $leave_categories = Leave_category::all();

        return
        [
            [
                'status' => 'OK',
                'leave_categories' => $leave_categories,
            ]
        ];
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create leave category');

        $validator = $this->validateLeaveCategory($request);

        if ($validator->fails()) return $this->getErrorMessage($validator->errors());

        $leave_category = Leave_category::create($request->all());

        return
        [
            [
                'status' => 'OK',
                'leave_category' => $leave_category,
            ]
        ];
    }

    public function show($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create leave category');

        $leave_type = Leave_category::findOrFail($id)->leave_type;

        return
        [
            [
                'status' => 'OK',
                'leave_type' => $leave_type,
            ]
        ];
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update Leave Category');

        $leave_category = Leave_category::findOrFail($id);
        $validator = $this->validateLeaveCategory($request);

        if($validator->fails()) return $this->getErrorMessage($validator->errors());

        $old_leave_category = $leave_category->leave_type;
        $leave_category->update($request->all());

        return
        [
            [
                'status' => 'OK',
                'old_leave_category' => $old_leave_category,
                'new_leave_category' => $leave_category->leave_type,
            ]
        ];
    }

    public function destroy(Leave_category $leave_category)
    {
        //
    }

    private function validateLeaveCategory(Request $request)
    {
        return Validator::make($request->all(), [
            'leave_type' => 'required|string|unique:leave_categories',
        ]);
    }
}
























//
