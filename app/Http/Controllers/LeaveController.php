<?php

namespace App\Http\Controllers;

use App\Leave;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use Validator;
use Carbon\Carbon;
use DateTime;
use App\MyErrorObject;
use App\LeaveCount;

class LeaveController extends Controller
{
    use CustomsErrorsTrait;
    private $myObject;

    private $decision = array('Rejected', 'Accepted', 'Pending');
    // private $month_name = array('Nothing', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    private $dayOfWeek = [
                            'Sun' => 'sunday',
                            'Mon' => 'monday',
                            'Tue' => 'tuesday',
                            'Wed' => 'wednesday',
                            'Thu' => 'thursday',
                            'Fri' => 'friday',
                            'Sat' => 'saturday',
    ];


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->myObject = new MyErrorObject;
    }

    public function index()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view all leaves');

        $this->renewLeaveCountAll();

        $leaves = Leave::all();

        $i = 0; $infos = [];

        foreach($leaves as $leave) {

            if(! $leave->user) continue;

            $infos[$i] = new User;

            $infos[$i]->id = $leave->id;
            $infos[$i]->user_id = $leave->user->id;
            $infos[$i]->full_name = $leave->user->full_name;
            $infos[$i]->department_name = $leave->user->department->department_name;
            $infos[$i]->designation = $leave->user->designation->designation;
            $infos[$i]->leave_type = $leave->leave_category->leave_type;
            $infos[$i]->leave_description = $leave->leave_description;
            $infos[$i]->start_date = $leave->start_date;
            $infos[$i]->end_date = $leave->end_date;
            $infos[$i]->leave_length = $this->getActualLeavesBetweenTwoDates($leave->user->working_day, $leave->start_date, $leave->end_date);
            $infos[$i]->leave_available = $leave->user->leave_counts->where('leave_category_id', $leave->leave_category_id)->first()->leave_left;
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
        $this->renewLeaveCountAll();

        $validate_attributes = $this->validateLeave();
        $leave_count = auth()->user()->leave_counts->where('leave_category_id', $validate_attributes['leave_category_id'])->count();

        if(! $leave_count) return $this->getErrorMessage('This user has no leave left record with this leave category');

        $validate_attributes['user_id'] = auth()->id();
        $validate_attributes['month'] = (new DateTime($validate_attributes['start_date']))->format('M');
        $validate_attributes['year'] = (new DateTime($validate_attributes['start_date']))->format('Y');
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

    public function show()
    {
        $this->renewLeaveCountAll();

        $user = auth()->user();
        $leaves = $user->leaves;

        for($i = 0; $i < sizeof($leaves); $i++){
            $leaves[$i]->requested_duration = $this->getActualLeavesBetweenTwoDates(
                $user->working_day, $leaves[$i]->start_date, $leaves[$i]->end_date
            );
            $leaves[$i]->leave_available = $user->leave_counts->where(
                'leave_category_id', $leaves[$i]->leave_category_id
            )->first()->leave_left;
        }

        return
        [
            [
                'status' => 'OK',
                'leaves' => $leaves,
            ]
        ];
    }

    public function showCountsDuration($leave_category_id, $start_date, $end_date)
    {
        $this->renewLeaveCountAll();

        $user = auth()->user();
        $leave_count = $user->leave_counts->where('leave_category_id', $leave_category_id)->first();
        $days_diff = $this->getActualLeavesBetweenTwoDates($user->working_day, $start_date, $end_date);

        return
        [
            [
                'status' => 'OK',
                'leave_left' => $leave_count->leave_left,
                'duration' => $days_diff,
            ]
        ];
    }

    public function update(Request $request, $id)
    {
        $this->renewLeaveCountAll();

        $leave = Leave::find($id);

        if($leave->user_id != auth()->id()) return $this->getErrorMessage('You can\'t update others leave information.');
        if($leave->approval_status != $this->decision[2]) return $this->getErrorMessage('Leave already responsed, you can\'t update information.');

        $validate_attributes = request()->validate([
            'leave_category_id' => 'string', 'leave_description' => 'string', 'start_date' => 'date', 'end_date' => 'date',
        ]);

        if(!$this->validateDatesWhileUpdating($request, $leave)) return $this->getErrorMessage('start date can\'t be greater than end date');

        if($request->filled('start_date'))
        {
            $validate_attributes['month'] = (new DateTime($validate_attributes['start_date']))->format('M');
            $validate_attributes['year'] = (new DateTime($validate_attributes['start_date']))->format('Y');
        }

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

        $this->renewLeaveCountAll();

        $leave = Leave::find($id);
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

        $this->renewLeaveCountAll();

        $leave = Leave::find($id);

        if($leave->approval_status != $this->decision[1]) return $this->getErrorMessage('This leave is not accepted yet. no cancel option');

        $available_LeaveToBeCancelledFirst = Leave::where([

                ['user_id', $leave->user_id],
                ['leave_category_id', $leave->leave_category_id],

            ])->orderBy('last_accepted_at', 'desc')->first();

        if($available_LeaveToBeCancelledFirst->id != $leave->id)
        {
            return $this->getErrorMessage('This leave can\'t be canceled now, leave no '.$available_LeaveToBeCancelledFirst->id.' should be cancelled first.');
        }

        // $days_diff = $this->getDaysDiffOfTwoDates($leave->start_date, $leave->end_date);
        $days_diff = $this->getActualLeavesBetweenTwoDates($leave->user->working_day, $leave->start_date, $leave->end_date);

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

    public function removeLeave($id)
    {
        $this->renewLeaveCountAll();

        $leave = Leave::find($id);

        if($leave->user->id != auth()->id()) return $this->getErrorMessage('Permission Denied');

        if($leave->approval_status != $this->decision[2])
        {
            return $this->getErrorMessage('Leave already responsed, you can\'t remove it');
        }

        $leave->delete();

        return
        [
            [
                'status' => 'OK',
                'message' => 'Leave Removed',
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
        $this->renewLeaveCountAll();

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
        $this->renewLeaveCountAll();

        // $requested_days = $this->getDaysDiffOfTwoDates($leave->start_date, $leave->end_date);
        $requested_days = $this->getActualLeavesBetweenTwoDates($leave->user->working_day, $leave->start_date, $leave->end_date);
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

    // private function getDaysDiffOfTwoDates($start, $finish)                         // Returns actual difference of two dates
    // {
    //     $start = new DateTime($start);
    //     $finish = new DateTime($finish);
    //     $interval = $start->diff($finish);
    //
    //     return (int)$interval->format('%a') + 1;
    // }

    private function getActualLeavesBetweenTwoDates($working_day, $start, $finish)  // Returns calculated working days between two dates
    {
        $this->renewLeaveCountAll();

        $start = new DateTime($start);
        $finish = new DateTime($finish);

        $actualLeaveCount = 0;

        while($start <= $finish)
        {
            if($working_day[$this->dayOfWeek[$start->format('D')]] == 'true' )
            {
                $actualLeaveCount++;
            }

            $start = date_add($start, date_interval_create_from_date_string('1 day'));
        }

        return $actualLeaveCount;
    }

    private function renewLeaveCountAll()
    {
        $leave_counts = LeaveCount::all();

        foreach ($leave_counts as $leave_count) {
            $this->renewLeaveCountAnEmployee($leave_count);
        }
    }

    private function renewLeaveCountAnEmployee($leave_count)
    {
        $expired_seconds = strtotime($leave_count->leave_count_expired);
        $today_seconds = strtotime(date('Y-m-d'));

        if($today_seconds < $expired_seconds) return;

        $validate_attributes = [];
        $validate_attributes['leave_count_start'] = $leave_count->leave_count_expired;
        $new_date_seconds = strtotime('+ 1 year', $expired_seconds); // second param should be in seconds
        $validate_attributes['leave_count_expired'] = date('Y-m-d', $new_date_seconds);

        if($leave_count->leave_category_id == '1')
        {
            $validate_attributes['leave_left'] = $this->myObject->casual_gift;
            $leave_count = $leave_count->update($validate_attributes);
        }
        else if($leave_count->leave_category_id == '2')
        {
            $validate_attributes['leave_left'] = $this->myObject->sick_gift;
            $leave_count = $leave_count->update($validate_attributes);
        }
    }
}

















//
