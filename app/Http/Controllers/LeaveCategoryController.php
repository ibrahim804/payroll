<?php

namespace App\Http\Controllers;

use App\Leave_category;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\MyErrorObject;
use DB;

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

        $index_gender = (auth()->user()->gender == 'male') ? 0 : 1;
        $restricted_type = $myObject->gender_specialized_leave_categories[(int)!$index_gender];
        $semi_restricted_type = $myObject->gender_specialized_leave_categories[(int)$index_gender];

        $special_category = Leave_category::where('leave_type', $semi_restricted_type)->first();
        $already_taken = auth()->user()->leave_counts->where('leave_category_id', $special_category->id)->first()->times_already_taken;
        $can_take = $special_category->times_can_take;
        $condition = ($already_taken < $can_take) ? "true" : "leave_type != '$semi_restricted_type'";

        $leave_categories = DB::select("
            select * from leave_categories
            where leave_type != '$restricted_type'
            and $condition
        ");

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
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

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
        // REDIRECTS TO createAfterNewLeaveCategoryCreation OF LEAVE COUNT
    }

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
