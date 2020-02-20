<?php

namespace App\Http\Controllers;

use App\Leave_category;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\MyErrorObject;

class LeaveCategoryController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        $myObject = new MyErrorObject;
        $leave_categories = null;

        if(auth()->user()->gender == 'female')
        {
            $leave_categories = Leave_category::where('leave_type', '<>', $myObject->gender_specialized_leave_categories[0])->get();
        }
        else if(auth()->user()->gender == 'male')
        {
            $leave_categories = Leave_category::where('leave_type', '<>', $myObject->gender_specialized_leave_categories[1])->get();
        }

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

        $validate_attributes = $this->validateLeaveCategory();
        $myObject = new MyErrorObject;

        if(
            $validate_attributes['leave_type'] == $myObject->gender_specialized_leave_categories[0] or
            $validate_attributes['leave_type'] == $myObject->gender_specialized_leave_categories[1]
        ) {
            if($request->filled('times_can_take')) $validate_attributes['times_can_take'] = $request->input('times_can_take');
            else return $this->getErrorMessage('You have to tell how much time a user can take this type of leave');
        }

        $leave_category = Leave_category::create($validate_attributes);

        return redirect('api/leave-count/leave-category/'.$leave_category->id);
    }

    public function show($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create leave category');

        $leave_type = Leave_category::find($id)->leave_type;

        return
        [
            [
                'status' => 'OK',
                'leave_type' => $leave_type,
            ]
        ];
    }

    // public function update(Request $request, $id)
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update Leave Category');
    //
    //     $leave_category = Leave_category::findOrFail($id);
    //     $validator = $this->validateLeaveCategory($request);
    //
    //     if($validator->fails()) return $this->getErrorMessage($validator->errors());
    //
    //     $old_leave_category = $leave_category->leave_type;
    //     $leave_category->update($request->all());
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'old_leave_category' => $old_leave_category,
    //             'new_leave_category' => $leave_category->leave_type,
    //         ]
    //     ];
    // }

    // public function destroy($id)
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to delete leave category');
    //
    //     Leave_category::findOrFail($id)->delete();
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'message' => 'Requested leave category deleted successfully',
    //         ]
    //     ];
    // }
    //
    // public function restore($id)
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to restore leave category');
    //
    //     Leave_category::onlyTrashed()->where('id', $id)->restore();
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'message' => 'Requested leave category is restored successfully.',
    //         ]
    //     ];
    // }
    //
    // public function trashedIndex()
    // {
    //     $leave_categories = Leave_category::onlyTrashed()->get();
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'leave_categories' => $leave_categories,
    //         ]
    //     ];
    // }

    private function validateLeaveCategory()
    {
        return request()->validate([
            'leave_type' => 'required|string|unique:leave_categories',
            'default_limit' => 'required|string',
            'times_can_take' => 'string',
        ]);
    }
}




















//
