<?php

namespace App\Http\Controllers;

use App\Leave;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use Validator;
use Carbon\Carbon;
use DateTime;

class LeaveController extends Controller
{
    use CustomsErrorsTrait;

    private $decision = array('Rejected', 'Accepted', 'Pending');
    private $month_name = array('Nothing', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view all leaves');

        $leaves = Leave::all();

        $i = 0; $infos = [];

        foreach($leaves as $leave) {

            $infos[$i] = new User;

            $infos[$i]->id = $leave->user->id;
            $infos[$i]->full_name = $leave->user->full_name;
            $infos[$i]->department_name = $leave->user->department->department_name;
            $infos[$i]->designation = $leave->user->designation->designation;
            $infos[$i]->leave_type = $leave->leave_category->leave_type;
            $infos[$i]->leave_description = $leave->leave_description;
            $infos[$i]->start_date = $leave->start_date;
            $infos[$i]->end_date = $leave->end_date;
            $infos[$i]->approval_status = $leave->approval_status;

            $i++;
        }

        return
        [
            [
                'status' => 'OK',
                'leaves' => $infos,
            ]
        ];
    }

    public function store(Request $request)
    {
        $validate_attributes = $this->validateLeave();
        $leave_count = auth()->user()->leave_counts->where('leave_category_id', $validate_attributes['leave_category_id'])->count();

        if(! $leave_count) return $this->getErrorMessage('This user has no leave left record with this leave category');

        $validate_attributes['user_id'] = auth()->id();
        $validate_attributes['month'] = date("M", strtotime('+6 hours'));
        $validate_attributes['application_date'] = date('Y-m-d H:i:s', strtotime('+6 hours'));

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

    public function update(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        if($leave->user_id != auth()->id()) return $this->getErrorMessage('You can\'t update others leave information.');
        if($leave->approval_status != $this->decision[2]) return $this->getErrorMessage('Leave already responsed, you can\'t update information.');

        $validate_attributes = request()->validate([
            'leave_category_id' => 'string', 'leave_description' => 'string', 'start_date' => 'date', 'end_date' => 'date',
        ]);

        if(!$this->validateDatesWhileUpdating($request, $leave)) return $this->getErrorMessage('start date can\'t be greater than end date');

        $leave->update($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'user_id' => $leave->user->id,
                'leave_type' => $leave->leave_category->leave_type,
                'description' => $leave->leave_description,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
            ]
        ];
    }

    public function updateApprovalStatus(Request $request, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update approval status');

        $leave = Leave::findOrFail($id);
        $value = request()->validate(['decision' => 'required|string']);
        $decision_str = $this->decision[(int) $value['decision']];

        if($leave->approval_status == $this->decision[1]) return $this->getErrorMessage('Leave already Accepted, you can\'t update anymore');

        if($decision_str == $this->decision[1])
        {
            $leave->unpaid_count = $this->calculateUnpaidLeave($leave);
            $leave->last_accepted_at = date("Y-m-d H:i:s", strtotime("+6 hours"));
        }

        $leave->approval_status = $decision_str;
        $leave->save();

        return
        [
            [
                'status' => 'OK',
                'approval_status' => $leave->approval_status,
            ]
        ];
    }

    public function cancelLeave($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to cancel leave');

        $leave = Leave::findOrFail($id);

        if($leave->approval_status != $this->decision[1]) return $this->getErrorMessage('This leave is not accepted yet. no cancel option');

        $available_LeaveToBeCancelledFirst = Leave::where([

                ['user_id', $leave->user_id],
                ['leave_category_id', $leave->leave_category_id],

            ])->orderBy('last_accepted_at', 'desc')->first();

        if($available_LeaveToBeCancelledFirst->id != $leave->id)
        {
            return $this->getErrorMessage('This leave can\'t be canceled now, leave no '.$available_LeaveToBeCancelledFirst->id.' should be cancelled first.');
        }

        $days_diff = $this->getDaysDiffOfTwoDates($leave->start_date, $leave->end_date);

        $leave_count = $leave->user->leave_counts->where('leave_category_id', $leave->leave_category_id)->first();
        $leave_count->leave_left = $leave_count->leave_left + ($days_diff - $leave->unpaid_count);
        $leave->unpaid_count = 0;
        $leave->approval_status = $this->decision[0];
        $leave->last_accepted_at = NULL;

        $leave_count->save();
        $leave->save();

        return
        [
            [
                'status' => 'OK',
                'approval_status' => $leave->approval_status,
            ]
        ];
    }

    private function validateLeave()
    {
        return request()->validate([
            'leave_category_id' => 'required|string',
            'leave_description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    }

    private function validateDatesWhileUpdating($request, $leave)
    {
        if(!($request->filled('start_date') or $request->filled('end_date'))) return 1;

        else if($request->filled('start_date') and $request->filled('end_date'))
        {
            return ($request->input('end_date') >= $request->input('start_date')) ? 1 : 0;
        }

        else if($request->filled('start_date'))
        {
            return ($leave->end_date >= $request->input('start_date')) ? 1 : 0;
        }

        else if($request->filled('end_date'))
        {
            return ($request->input('end_date') >= $leave->start_date) ? 1 : 0;
        }
    }

    private function calculateUnpaidLeave($leave)
    {
        $requested_days = $this->getDaysDiffOfTwoDates($leave->start_date, $leave->end_date);
        $leave_count = $leave->user->leave_counts->where('leave_category_id', $leave->leave_category_id)->first();

        if($requested_days <= $leave_count->leave_left)
        {
            $leave_count->update(['leave_left' => ($leave_count->leave_left - $requested_days)]);
            return 0;
        }

        $temp = $requested_days - $leave_count->leave_left;
        $leave_count->update(['leave_left' => 0]);

        return $temp;
    }

    private function getDaysDiffOfTwoDates($start, $finish)
    {
        $start = new DateTime($start);
        $finish = new DateTime($finish);
        $interval = $start->diff($finish);

        return (int)$interval->format('%a') + 1;
    }
}

















//
