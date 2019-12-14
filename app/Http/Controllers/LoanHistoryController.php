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

    public function store()     // PayBack Request
    {
        $onPendingCount = LoanPayBack::where([
            ['user_id', auth()->id()],
            ['approval_status', $this->decision[2]],
        ])->count();

        if($onPendingCount > 0) return $this->getErrorMessage('Your last loan payment request on pending');

        $validate_attributes = $this->validateLoanHisrory();

        $isExist = LoanHistory::where([
            ['user_id', auth()->id()],
            ['month', date("M", strtotime('+6 hours'))],
            ['year', date("Y", strtotime('+6 hours'))],
        ])->count();

        if($isExist > 0) return $this->getErrorMessage('Already paid for this month');

        $immediate = LoanHistory::where('user_id', auth()->id())->latest()->first();

        if($immediate->loan_status == $this->myObject->loan_statuses[2])
        {
            return $this->getErrorMessage('No accepted loan request found.');
        }

        $validate_attributes['user_id'] = auth()->id();
        $validate_attributes['month'] = date("M", strtotime('+6 hours'));
        $validate_attributes['year'] = date("Y", strtotime('+6 hours'));
        $validate_attributes['month_count'] = (int)$this->calculateMonthDiff(
            $immediate->year, $immediate->month, $validate_attributes['year'], $validate_attributes['month']
        ) + (int)$immediate['month_count'];
        $validate_attributes['actual_loan_amount'] = $immediate['actual_loan_amount'];
        $validate_attributes['yearly_interest_rate'] = $immediate['yearly_interest_rate'];
        $validate_attributes['paid_amount'] = (double)$validate_attributes['paid_amount'] + (double)$immediate['paid_amount'];
        $validate_attributes['current_loan_amount'] = $this->calculateCurrentLoanAmount($validate_attributes);
        $validate_attributes['loan_status'] = $this->myObject->loan_statuses[1];

        if($validate_attributes['current_loan_amount'] <= 0)
        {
            $validate_attributes = $this->fixErrorCalculation($validate_attributes);
            $validate_attributes['loan_status'] = $this->myObject->loan_statuses[2];
        }

        $validate_attributes['approval_status'] = $this->decision[2];   // NEW
        $loan_pay_back = LoanPayBack::create($validate_attributes);
        // $loan_history = LoanHistory::create($validate_attributes); Previous

        return
        [
            [
                'status' => 'OK',
                'loan_pay_back' => $loan_pay_back,
            ]
        ];
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

        return
        [
            [
                'status' => 'OK',
                'loan_pay_backs' => $loan_pay_backs,
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

    private function validateLoanHisrory()
    {
        return request()->validate ([
            'paid_amount' => 'required|string',
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

    private function fixErrorCalculation($validate_attributes)
    {
        $validate_attributes['current_loan_amount'] = 0;
        $validate_attributes['paid_amount'] =
            $validate_attributes['actual_loan_amount']
        +   $validate_attributes['actual_loan_amount'] * ($validate_attributes['yearly_interest_rate'] / 12)
        *   $validate_attributes['month_count'];
        return $validate_attributes;
    }
}











//
