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

    private function validateLoanHistory()
    {
        return request()->validate([
            'user_id' => 'required|string',
        ]);
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
