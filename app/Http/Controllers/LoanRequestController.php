<?php

namespace App\Http\Controllers;

use App\LoanRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\ProvidentFund;
use App\LoanHistory;

class LoanRequestController extends Controller
{
    use CustomsErrorsTrait;

    private $decision = array('Rejected', 'Accepted', 'Pending');

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $loan_requests = LoanRequest::where('approval_status', $this->decision[2])->get();

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

        if(auth()->id() != $validate_attributes['user_id']) return $this->getErrorMessage('his his, her her');

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

        if($loan_history and $loan_history->loan_status != 'finish')
        {
            return $this->getErrorMessage('You have already taken a loan and didn\'t pay it fully');
        }

        $loan_request = LoanRequest::create($validate_attributes);
        $loan_request->update(['approval_status' => 'created_at']);

        return
        [
            [
                'status' => 'OK',
                'loan_request' => $loan_request,
            ]
        ];
    }

    public function show($user_id)
    {
        if(auth()->id() != $user_id) return $this->getErrorMessage('Can\'t show others Loan request');

        $loan_request = LoanRequest::where('user_id', $user_id)->latest()->first();

        if(! $loan_request) return $this->getErrorMessage('You don\'t have any loan request yet');

        return
        [
            [
                'status' => 'OK',
                'loan_request' => $loan_request,
            ]
        ];
    }

    public function update($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = request()->validate(['approval_status' => 'required|string']);
        $loan_request = LoanRequest::find($id);
        $loan_request->update([
            'approval_status' => $this->decision[(int)$validate_attributes['approval_status']],
        ]);

        return
        [
            [
                'status' => 'OK',
                'loan_request' => $loan_request,
            ]
        ];
    }

    private function validateLoanRequest()
    {
        return request()->validate ([
            'user_id' => 'required|string',
            'requested_amount' => 'required|string',
        ]);
    }
}
