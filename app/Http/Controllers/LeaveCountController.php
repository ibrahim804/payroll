<?php

namespace App\Http\Controllers;

use App\LeaveCount;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\User;
use App\Leave_category;
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
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view all leave counts');

        $leave_counts = LeaveCount::all();

        $i = 0; $infos = [];

        foreach($leave_counts as $leave_count) {

            $infos[$i] = new LeaveCount;

            $infos[$i]->id = $leave_count->id;
            $infos[$i]->full_name = $leave_count->user->full_name;
            $infos[$i]->leave_type = $leave_count->leave_category->leave_type;
            $infos[$i]->leave_left = $leave_count->leave_left;
            $infos[$i]->leave_count_start = $leave_count->leave_count_start;
            $infos[$i]->leave_count_expired = $leave_count->leave_count_expired;

            $i++;
        }

        return $infos;
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create any leave count');

        $validate_attributes = $this->validateLeaveCount();
        $leave_category = Leave_category::find($validate_attributes['leave_category_id']);

        if(! $leave_category) return $this->getErrorMessage('No Leave category found.');
        if($leave_category->leave_type == 'Unpaid') return $this->getErrorMessage('Unpaid leave has no leave count.');

        $flag = $leave_category->leave_counts->where('user_id', $validate_attributes['user_id'])->count();

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

    public function show($user_id, $leave_category_id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false' and auth()->id() != $user_id)
        {
            return $this->getErrorMessage('You don\'t have permission to check others leave count');
        }

        $leave_count = LeaveCount::where([
            ['user_id', $user_id],
            ['leave_category_id', $leave_category_id],
        ])->first();

        if(! $leave_count) return $this->getErrorMessage('This user has no leave count with this leave category.');

        return
        [
            [
                'status' => 'OK',
                'leave_left' => $leave_count->leave_left,
                'leave_count_start' => $leave_count->leave_count_start,
                'leave_count_expired' => $leave_count->leave_count_expired,
            ]
        ];
    }

    public function update($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update any leave count');

        $leave_count = LeaveCount::findOrFail($id);
        $validate_attributes = request()->validate(['leave_left' => 'string']);
        $leave_count->update($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'leave_left' => $leave_count->leave_left,
            ]
        ];
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
