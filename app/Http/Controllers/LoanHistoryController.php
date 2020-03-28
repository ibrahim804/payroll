<?php

namespace App\Http\Controllers;

use App\LoanHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\MyErrorObject;
use App\User;
use App\LoanPayBack;

class LoanHistoryController extends Controller
{
    use CustomsErrorsTrait;
    private $myObject;
    private $decision = array('Rejected', 'Accepted', 'Pending');

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
        $this->myObject = new MyErrorObject;
    }

    public function index()
    {
        $loan_histories = LoanHistory::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        return
        [
            [
                'status' => 'OK',
                'loan_histories' => $loan_histories,
            ]
        ];
    }

    public function store()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = $this->validateLoanHistory();

        $user = User::find($validate_attributes['user_id']);
        $size = $user->loan_histories->count();

        if($size == 0) return $this->getErrorMessage('User has no loan history');

        $latest_loan_history = $user->loan_histories[$size - 1];

        if($latest_loan_history->loan_status == $this->myObject->loan_statuses[2]) return $this->getErrorMessage('Latest loan is already finished');

        $running_month = date("M", strtotime('+6 hours'));
        $running_year = date("Y", strtotime('+6 hours'));

        if(
            $latest_loan_history->month == $running_month &&
            $latest_loan_history->year == $running_year
        ) {
            return $this->getErrorMessage('For first month of loan taken, user doesn\'t need to pay');
        }

        $validate_attributes['month'] = $running_month;
        $validate_attributes['year'] = $running_year;
        $validate_attributes['month_count'] = $latest_loan_history->month_count + 1;
        $validate_attributes['contract_duration'] = $latest_loan_history->contract_duration;
        $validate_attributes['actual_loan_amount'] = $latest_loan_history->actual_loan_amount;
        $validate_attributes['paid_this_month'] = $validate_attributes['actual_loan_amount'] / $validate_attributes['contract_duration'];
        $validate_attributes['total_paid_amount'] = $latest_loan_history->total_paid_amount + $validate_attributes['paid_this_month'];
        $validate_attributes['current_loan_amount'] = $validate_attributes['actual_loan_amount'] - $validate_attributes['total_paid_amount'];
        $validate_attributes['loan_status'] = $this->myObject->loan_statuses[1];

        LoanHistory::create($this->fixCalculationError($validate_attributes, $latest_loan_history->total_paid_amount));

        return $this->showSuccessMessage('Loan Pay Back record created successfully');
    }

    public function checkEligibility()
    {
        $isExist = LoanHistory::where([
            ['user_id', auth()->id()],
            ['month', date("M", strtotime('+6 hours'))],
            ['year', date("Y", strtotime('+6 hours'))],
        ])->count();

        if($isExist > 0) return $this->getErrorMessage('Already paid for this month of year');

        return
        [
            [
                'status' => 'OK',
                'message' => 'You are eligible',
            ]
        ];
    }

    public function getAllPendingPayBacks()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $loan_pay_backs = LoanPayBack::where('approval_status', $this->decision[2])->get();
        $allowed_pendings = [];
        $index = 0;

        foreach ($loan_pay_backs as $loan_pay_back) {
            if(
                $loan_pay_back->month != date("M", strtotime('+6 hours')) ||
                $loan_pay_back->year != date("Y", strtotime('+6 hours'))
            ) {
                $loan_pay_back->update(['approval_status' => $this->decision[0]]);
            } else {
                $loan_pay_back->full_name = $loan_pay_back->user->full_name;
                $loan_pay_back->department = $loan_pay_back->user->department->department_name;
                $loan_pay_back->designation = $loan_pay_back->user->designation->designation;
                $loan_pay_back->previoud_paid = $loan_pay_back->user->loan_histories()->latest()->first()->paid_amount;
                $allowed_pendings[$index] = $loan_pay_back;
                $index ++;
            }
        }

        return
        [
            [
                'status' => 'OK',
                'loan_pay_backs' => $allowed_pendings,
            ]
        ];
    }

    public function acceptLoanPayBackRequest($id)
    {
        $loan_pay_back = LoanPayBack::find($id);

        if(! $loan_pay_back) return $this->getErrorMessage('Not Found');

        $validate_attributes = [];
        $validate_attributes['user_id'] = $loan_pay_back->user_id;
        $validate_attributes['month'] = $loan_pay_back->month;
        $validate_attributes['year'] = $loan_pay_back->year;
        $validate_attributes['month_count'] = $loan_pay_back->month_count;
        $validate_attributes['actual_loan_amount'] = $loan_pay_back->actual_loan_amount;
        $validate_attributes['yearly_interest_rate'] = $loan_pay_back->yearly_interest_rate;
        $validate_attributes['current_loan_amount'] = $loan_pay_back->current_loan_amount;
        $validate_attributes['paid_amount'] = $loan_pay_back->paid_amount;
        $validate_attributes['loan_status'] = $loan_pay_back->loan_status;

        $loan_history = LoanHistory::create($validate_attributes);
        $loan_pay_back->update(['approval_status' => $this->decision[1]]);

        return
        [
            [
                'status' => 'OK',
                'loan_history' => $loan_history,
            ]
        ];
    }

    private function validateLoanHistory()
    {
        return request()->validate([
            'user_id' => 'required|string',
        ]);
    }

    private function calculateMonthDiff($i_y, $i_m, $v_y, $v_m)
    {
        $all_months = array('Dummy', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $count = ($v_y - $i_y) * 12;
        $count += array_search($v_m, $all_months) - array_search($i_m, $all_months);
        return $count;
    }

    private function calculateCurrentLoanAmount($validate_attributes)
    {
        return  $validate_attributes['actual_loan_amount']
            +   ($validate_attributes['actual_loan_amount'] * ($validate_attributes['yearly_interest_rate'] / 12)
            *   $validate_attributes['month_count'])
            -   $validate_attributes['paid_amount'];
    }

    private function fixCalculationError($validate_attributes, $latest_total_paid)
    {
        if($validate_attributes['month_count'] != $validate_attributes['contract_duration']) return $validate_attributes;

        $validate_attributes['current_loan_amount'] = 0;
        $validate_attributes['paid_this_month'] = $validate_attributes['actual_loan_amount'] - $latest_total_paid;
        $validate_attributes['total_paid_amount'] = $validate_attributes['actual_loan_amount'];
        $validate_attributes['loan_status'] = $this->myObject->loan_statuses[2];

        return $validate_attributes;
    }
}











//
