<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\User;
use Carbon\Carbon;

class PaymentController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $payments_user_id = Payment::where([
            ['month', date("M", strtotime('+6 hours'))],
            ['year', date("Y", strtotime('+6 hours'))],
        ])->pluck('user_id');

        return
        [
            [
                'status' => 'OK',
                'payments_user_id' => $payments_user_id,
            ]
        ];
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = $this->validatePayment();
        $user = User::find($validate_attributes['user_id']);

        if(! $user) return $this->getErrorMessage('User not found');
        if(! $user->salary) return $this->getErrorMessage('Salary infor of this user not found');

        $validate_attributes['payment_date'] = date("Y-m-d H:i:s", strtotime('+6 hours'));
        $validate_attributes['month'] = date("M", strtotime('+6 hours'));
        $validate_attributes['year'] = date("Y", strtotime('+6 hours'));

        $isExist = Payment::where([
            ['user_id', $validate_attributes['user_id']],
            ['month', $validate_attributes['month']],
            ['year', $validate_attributes['year']],
        ])->first();

        if($isExist) return $this->getErrorMessage('You have paid this user already for this month');

        $payment = Payment::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'payment' => $payment,
            ]
        ];
    }


    public function show(Payment $payment)
    {
        //
    }

    private function validatePayment()
    {
        return request()->validate([
            'user_id' => 'required|string',
            'employee_monthly_cost' => 'required|string',
            'payable_amount' => 'required|string',
        ]);
    }
}
