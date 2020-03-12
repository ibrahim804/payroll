<?php

namespace App\Http\Controllers;

use App\LoanRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\CustomsErrorsTrait;
use App\ProvidentFund;
use App\LoanHistory;
use App\MyErrorObject;
use App\User;

class LoanRequestController extends Controller
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
        $validate_attributes['user_id'] = auth()->id();
        $validate_attributes['application_date'] = date('Y-m-d H:i:s', strtotime('+6 hours'));
        $validate_attributes['requested_amount'] = (double)$validate_attributes['requested_amount'];
        $provident_fund = ProvidentFund::where(
            'user_id', $validate_attributes['user_id']
        )->latest()->first();
        $validate_attributes['approval_status'] = $this->decision[2];

        if(! $provident_fund) return $this->getErrorMessage('No Provident Fund Record Found');

        $validate_attributes['provident_fund'] = $provident_fund->closing_balance;

        if($validate_attributes['provident_fund'] < $validate_attributes['requested_amount'])
        {
            return $this->getErrorMessage('Can\'t take loan more than that you have');
        }

        $loan_history = LoanHistory::where('user_id', $validate_attributes['user_id'])->latest()->first();
        $count = LoanHistory::where('user_id', $validate_attributes['user_id'])->count();

        if($count and $loan_history->loan_status != $this->myObject->loan_statuses[2])
        {
            return $this->getErrorMessage('You have already taken a loan and didn\'t pay it fully');
        }

        $count = LoanRequest::where([
            ['user_id', $validate_attributes['user_id']],
            ['approval_status', $this->decision[2]],
        ])->count();

        if($count) return $this->getErrorMessage('You have already a pending request');

        $loan_request = LoanRequest::create($validate_attributes);
        // $loan_request->update(['approval_status' => 'created_at']);

        return
        [
            [
                'status' => 'OK',
                'loan_request' => $loan_request,
            ]
        ];
    }

    // public function show($user_id) // no need
    // {
    //     if(auth()->id() != $user_id) return $this->getErrorMessage('Can\'t show others Loan request');
    //
    //     $loan_request = LoanRequest::where('user_id', $user_id)->latest()->first();
    //
    //     if(! $loan_request) return $this->getErrorMessage('You don\'t have any loan request yet');
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'loan_request' => $loan_request,
    //         ]
    //     ];
    // }

    public function update($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = request()->validate(['approval_status' => 'required|string']);

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
        ]);
    }

    private function createLoanHistory($loan_request)   // Look again what you did.
    {
        $validate_attributes = [];
        $validate_attributes['user_id'] = $loan_request->user_id;
        $validate_attributes['month'] = date("M", strtotime('+6 hours'));
        $validate_attributes['year'] = date("Y", strtotime('+6 hours'));
        $validate_attributes['month_count'] = 0;
        $validate_attributes['actual_loan_amount'] = $loan_request['requested_amount'];
        $validate_attributes['yearly_interest_rate'] = $this->myObject->pf_yearly_rate;
        $validate_attributes['current_loan_amount'] = $loan_request['requested_amount'];
        $validate_attributes['paid_amount'] = 0;
        $validate_attributes['loan_status'] = $this->myObject->loan_statuses[0];

        $loan_history = LoanHistory::create($validate_attributes);
        return $loan_history;
    }
}


//




//
