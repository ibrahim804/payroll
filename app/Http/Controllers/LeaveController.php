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
use App\Leave_category;

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
        if(auth()->user()->role->type == 'user') return $this->getErrorMessage('Permission Denied');

        $this->renewLeaveCountAll();

        $leaves = Leave::orderBy('created_at',  'desc')->get();

        $i = 0; $infos = [];

        foreach($leaves as $leave) {

            if(! $leave->user) continue;

            if(
                auth()->user()->role->type != 'admin' &&
                $leave->user->id_of_leader != auth()->id()
            ) {
                continue;
            }

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
            $infos[$i]->is_readonly = $leave->user->id_of_leader != auth()->id();

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

        $invalid_type = Leave_category::find($validate_attributes['leave_category_id'])->leave_type;

        if(
            ($invalid_type == $this->myObject->gender_specialized_leave_categories[0] and auth()->user()->gender == 'female') or
            ($invalid_type == $this->myObject->gender_specialized_leave_categories[1] and auth()->user()->gender == 'male')

        ) {
            return $this->getErrorMessage('Before taking this type of leave, change your gender first');
        }

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
        $leaves = Leave::where('user_id', auth()->id())->orderBy('created_at',  'desc')->get();

        for($i = 0; $i < sizeof($leaves); $i++){
            $leaves[$i]->requested_duration = $this->getActualLeavesBetweenTwoDates(
                $user->working_day, $leaves[$i]->start_date, $leaves[$i]->end_date
            );
            $leaves[$i]->leave_available = $user->leave_counts->where(
                'leave_category_id', $leaves[$i]->leave_category_id
            )->first()->leave_left;
            $leaves[$i]->leave_type = Leave_category::find($leaves[$i]->leave_category_id)->leave_type;
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
        $user = auth()->user();

        // $working_day = $user->working_day;

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

    public function updateApprovalStatus(Request $request, $id)
    {
        if(auth()->user()->role->type == 'user') return $this->getErrorMessage('Permission Denied');

        $leave = Leave::find($id);

        if($leave->user->id_of_leader != auth()->id())      // SCOPE DEFINED
        {
            return $this->getErrorMessage('Out Of Access');
        }

        $value = request()->validate(['decision' => 'required|string']);
        $decision_str = $this->decision[(int) $value['decision']];

        if($leave->approval_status == $this->decision[1]) return $this->getErrorMessage('Leave already Accepted, you can\'t update anymore');

        $invalid_type = $leave->leave_category->leave_type;

        if(
            ($invalid_type == $this->myObject->gender_specialized_leave_categories[0] and $leave->user->gender == 'female') or
            ($invalid_type == $this->myObject->gender_specialized_leave_categories[1] and $leave->user->gender == 'male')

        ) {
            return $this->getErrorMessage('Gender doesn\'t match');
        }

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

    public function cancelLeave($id)    // ADMIN-LEADER TASK
    {
        if(auth()->user()->role->type == 'user') return $this->getErrorMessage('Permission Denied');

        $leave = Leave::find($id);

        if($leave->user->id_of_leader != auth()->id())      // SCOPE DEFINED
        {
            return $this->getErrorMessage('Out Of Access');
        }

        if($leave->approval_status != $this->decision[1]) return $this->getErrorMessage('This leave is not accepted yet. no cancel option');

        if($this->attemptsToCancelUnTrackedLeave($leave))
        {
            return $this->getErrorMessage('Leave cancellation follows: last accept first reject');
        }

        $days_diff = $this->getActualLeavesBetweenTwoDates($leave->user->working_day, $leave->start_date, $leave->end_date);
        $leave_count = $leave->user->leave_counts->where('leave_category_id', $leave->leave_category_id)->first();
        $special_index = ($leave->user->gender == 'male') ? 0 : 1;

        if($leave_count->leave_category->leave_type == $this->myObject->gender_specialized_leave_categories[$special_index]) {
            $leave_count->times_already_taken = $leave_count->times_already_taken - 1;  // time already taken value must be at least 1. don't worry :)
            if($leave_count->times_already_taken == 0) {
                $leave_count->leave_left = $leave_count->leave_category->default_limit;
            } else {
                $leave_count->leave_left = 0;
            }
            $leave_count->save();
        } else {
            // $leave_count->leave_left = $leave_count->leave_left + ($days_diff - $leave->unpaid_count);      // CONSIDER ALL SIMILAR LEAVE CATEGORIES
            $this->updateLeaveCountsOfSameCategories($leave, $leave_count->leave_left + ($days_diff - $leave->unpaid_count));
        }

        $leave->unpaid_count = 0;
        $leave->approval_status = $this->decision[0];
        $leave->last_accepted_at = NULL;

        $leave->save();

        return
        [
            [
                'status' => 'OK',
                'approval_status' => $leave->approval_status,
            ]
        ];
    }

    private function attemptsToCancelUnTrackedLeave($leave)
    {
        if(in_array($leave->leave_category->leave_type, $this->myObject->gender_specialized_leave_categories))
        {
            $available_LeaveToBeCancelledFirst = Leave::where([

                ['user_id', $leave->user_id],
                ['leave_category_id', $leave->leave_category_id],

            ])->orderBy('last_accepted_at', 'desc')->first();
        }
        else
        {
            $same_categories_ids = Leave_category::whereIn('leave_type', $this->myObject->general_leave_catagories)
                                                    ->pluck('id');
            $available_LeaveToBeCancelledFirst = Leave::where('user_id', $leave->user_id)
                                                        ->whereIn('leave_category_id', $same_categories_ids)
                                                        ->orderBy('last_accepted_at', 'desc')
                                                        ->first();
        }

        return ($available_LeaveToBeCancelledFirst->id == $leave->id) ? 0 : 1;

    }

    public function removeLeave($id)    // LEADER-USER TASK
    {
        $leave = Leave::find($id);

        if($leave->user->id != auth()->id()) return $this->getErrorMessage('Permission Denied');

        if($leave->approval_status != $this->decision[2])
        {
            return $this->getErrorMessage('Leave already responded, you can\'t remove it');
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

    private function calculateUnpaidLeave($leave)
    {
        $requested_days = $this->getActualLeavesBetweenTwoDates($leave->user->working_day, $leave->start_date, $leave->end_date);
        $leave_count = $leave->user->leave_counts->where('leave_category_id', $leave->leave_category_id)->first();

        $response = $this->calculateForGenderSpecialLeave($leave, $requested_days);

        if($response >= 0) return $response;             // THIS IS SPECIAL CATEGORY, $response carry unpaid count for special category

        if($requested_days <= $leave_count->leave_left)
        {
            $this->updateLeaveCountsOfSameCategories($leave, ($leave_count->leave_left - $requested_days));
            return 0;
        }

        $temp = $requested_days - $leave_count->leave_left;
        $this->updateLeaveCountsOfSameCategories($leave, 0);

        return $temp;
    }

    private function updateLeaveCountsOfSameCategories($leave, $cnt)
    {
        $same_category_ids = Leave_category::whereIn('leave_type', $this->myObject->general_leave_catagories)->pluck('id');
        $leave_counts = $leave->user->leave_counts->whereIn('leave_category_id', $same_category_ids);

        foreach($leave_counts as $leave_count) {
            $leave_count->update(['leave_left' => $cnt]);
        }
    }

    private function calculateForGenderSpecialLeave($leave, $requested_days)   // misty khawan :)
    {
        if(! in_array($leave->leave_category->leave_type, $this->myObject->gender_specialized_leave_categories)) return -1;     // NORMAL CATEGORY

        $leave_count = $leave->user->leave_counts->where('leave_category_id', $leave->leave_category_id)->first();
        $temp = (int)$leave_count->leave_left;
        $leave_count->leave_left = 0;
        $leave_count->times_already_taken = $leave_count->times_already_taken + 1;
        $leave_count->save();

        // Returns a value of unpaid leave count for special category

        if($requested_days <= $temp) return 0;
        else return $requested_days - $temp;

    }

    private function getActualLeavesBetweenTwoDates($working_day, $start, $finish)  // Returns calculated working days between two dates
    {
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
        $allowed_leave_category_ids = Leave_category::whereIn('leave_type', $this->myObject->general_leave_catagories)->pluck('id');
        $leave_counts = LeaveCount::whereIn('leave_category_id', $allowed_leave_category_ids)->get();

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
        $validate_attributes['leave_left'] = $leave_count->leave_category->default_limit;

        $leave_count->update($validate_attributes);
    }
}

















//
