<?php

namespace App\Http\Controllers;

use App\LoanRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\CustomsErrorsTrait;
use App\Http\Controllers\SharedTrait;
use App\ProvidentFund;
use App\LoanHistory;
use App\MyErrorObject;
use App\User;

class LoanRequestController extends Controller
{
    use CustomsErrorsTrait, SharedTrait;

    private $myObject;

    private $decision = array('Rejected', 'Accepted', 'Pending');

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
        $this->myObject = new MyErrorObject;
    }

    public function index()     // BASICALLY RETURNS ALL PENDING REQUESTS
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $loan_requests = LoanRequest::where('approval_status', $this->decision[2])->get();

        foreach ($loan_requests as $loan_request) {
            $user = User::find($loan_request->user_id);
            $loan_request->full_name = $user->full_name;
            $loan_request->department = $user->department->department_name;
            $loan_request->designation = $user->designation->designation;
        }

        return
        [
            [
                'status' => 'OK',
                'loan_requests' => $loan_requests,
            ]
        ];
    }

    public function store()
    {
        $validate_attributes = $this->validateLoanRequest();
        $size = auth()->user()->loan_requests->count();

        if($size > 0)
        {
            $latest_request = auth()->user()->loan_requests[$size - 1];

            if($latest_request->approval_status == $this->decision[2])
            {
                return $this->getErrorMessage('You have already a pending request');
            }
        }

        $validate_attributes['user_id'] = auth()->id();
        $validate_attributes['application_date'] = date('Y-m-d H:i:s', strtotime('+6 hours'));
        $obj = $this->getLoanableAmountLimit()[0];
        $validate_attributes['available_amount'] = $obj['gross'];
        $validate_attributes['approval_status'] = $this->decision[2];

        if($validate_attributes['available_amount'] == -1) return $this->getErrorMessage('Salary doesn\'t exist');

        $size = auth()->user()->loan_histories->count();

        if($size > 0)
        {
            $latest_history = auth()->user()->loan_histories[$size - 1];

            if($latest_history->loan_status != $this->myObject->loan_statuses[2])
            {
                return $this->getErrorMessage('You have already taken a loan and didn\'t pay it fully');
            }
        }

        $loan_request = LoanRequest::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'message' => 'Loan Request Created',
            ]
        ];
    }

    public function show()
    {
        $count = LoanRequest::where([
            ['user_id', auth()->id()],
            ['approval_status', $this->decision[2]],
        ])->count();

        if($count > 0) return $this->getErrorMessage('You have already a pending request');

        return
        [
            [
                'status' => 'OK',
                'message' => 'Eligible to take loan',
            ]
        ];
    }

    public function update($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = request()->validate(['approval_status' => 'required|string']); // must be 0 or 1

        $loan_request = LoanRequest::find($id);

        if($loan_request['approval_status'] != $this->decision[2])
        {
            return $this->getErrorMessage('Can\'t update, Already Responded');
        }

        $loan_request->update([
            'approval_status' => $this->decision[(int)$validate_attributes['approval_status']],
        ]);

        $loan_history = null;

        if($loan_request->approval_status == $this->decision[1])
        {
            $loan_history = $this->createLoanHistory($loan_request);
        }

        return
        [
            [
                'status' => 'OK',
                'loan_request' => $loan_request,
                'loan_history' => $loan_history,
            ]
        ];
    }

    private function validateLoanRequest()
    {
        return request()->validate ([
            'requested_amount' => 'required|string',
            'contract_duration' => 'required|string',
        ]);
    }

    public function getLoanableAmountLimit()
    {
        if(! auth()->user()->salary)
        {
            return
            [
                [
                    'status' => 'FAILED',
                    'gross' => -1,
                ]
            ];
        }

        $join = Carbon::parse(auth()->user()->joining_date);
        $today = Carbon::now();
        $diff = $join->diffInYears($today);

        $gross = $this->calculateGross(auth()->user()->salary);
        $gross = ($diff >= 2) ? 1.5*$gross : $gross;

        return
        [
            [
                'status' => 'OK',
                'gross' => $gross,
            ]
        ];
    }

    private function createLoanHistory($loan_request)
    {
        $validate_attributes = [];
        $validate_attributes['user_id'] = $loan_request->user_id;
        $validate_attributes['month'] = date("M", strtotime('+6 hours'));
        $validate_attributes['year'] = date("Y", strtotime('+6 hours'));
        $validate_attributes['month_count'] = 0;
        $validate_attributes['contract_duration'] = $loan_request->contract_duration;
        $validate_attributes['actual_loan_amount'] = $loan_request->requested_amount;
        $validate_attributes['current_loan_amount'] = $loan_request->requested_amount;
        $validate_attributes['paid_this_month'] = 0;
        $validate_attributes['total_paid_amount'] = 0;
        $validate_attributes['loan_status'] = $this->myObject->loan_statuses[0];

        $loan_history = LoanHistory::create($validate_attributes);
        return $loan_history;
    }
}


//




//
