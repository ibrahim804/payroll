<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\Http\Controllers\SharedTrait;
use App\User;
use Carbon\Carbon;
use App\Leave;
use Illuminate\Support\Facades\Mail;
use App\Mail\Payment as PaymentMail;

class PaymentController extends Controller
{
    use CustomsErrorsTrait, SharedTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()     // BASICALLY RETURNS PAYMENTS OF THIS YEAR-MONTH
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $payments_user_id = Payment::where([
            ['month', date("M", strtotime('+6 hours'))],
            ['year', date("Y", strtotime('+6 hours'))],
        ])->pluck('user_id');

        $userCountMap = $this->getKeyUserIdValueUnpaidLeaveCount();

        return
        [
            [
                'status' => 'OK',
                'payments_user_id' => $payments_user_id,
                'userCountMap' => $userCountMap,
            ]
        ];
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = $this->validatePayment();
        $user = User::find($validate_attributes['user_id']);

        if(! $user) return $this->getErrorMessage('User not found');
        if(! $user->salary) return $this->getErrorMessage('This employee has no salary information, set salary info.');

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

    public function sendPaymentToMail()         // etake arekto jate tulte hobe
    {
        $validate_attributes = request()->validate([
          'user_id' => 'required|string',
          'unpaid_leave_count' => 'required|string',
        ]);

        $user = User::find($validate_attributes['user_id']);

        $payment = $user->payments()->latest()->first();

        Mail::to($user->email)->send(
            new PaymentMail($payment, $validate_attributes['unpaid_leave_count'], ($user->company) ? $user->company->name : '')
        );
    }

    public function getExportableData()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $data = NULL;
        $index = 0;
        $users = User::all();

        $userCountMap = $this->getKeyUserIdValueUnpaidLeaveCount();

        foreach ($users as $user) {

            if(! $user->salary) continue;

            $salary = $user->salary;
            $calculated_amounts = $this->calculatePayableAmount($salary);

            $salary->gross_salary = $calculated_amounts['gross_salary'];
            $salary->total_deduction = $calculated_amounts['total_deduction'];
            $salary->net_salary = $calculated_amounts['net_salary'];
            $salary->unpaid_leave_taken = ($userCountMap->has($user->id)) ? $userCountMap[$user->id] : 0;
            $salary->deduction_leave = $this->calculateLeaveDeduction(
                $salary->unpaid_leave_taken, $salary->gross_salary
            );
            $salary->payable_amount = $this->calculatePayableAmountAfterLeaveDeduction(
                $salary->unpaid_leave_taken, $salary->gross_salary, $salary->net_salary
            );

            $data[$index++] = $salary;
        }

        return
        [
            [
                'status' => 'OK',
                'sheet' => $data,
            ]
        ];
    }

    private function getKeyUserIdValueUnpaidLeaveCount()
    {
        $unpaid_leaves = Leave::where([
            ['month', date("M", strtotime('+6 hours'))],
            ['year', date("Y", strtotime('+6 hours'))],
            ['approval_status', 'Accepted'],
            ['unpaid_count', '>', 0],
        ])->get();

        $userCountMap = collect([]);

        foreach ($unpaid_leaves as $unpaid_leave) {
            if($userCountMap->has($unpaid_leave->user_id)) $userCountMap[$unpaid_leave->user_id] += (int)$unpaid_leave->unpaid_count;
            else $userCountMap[$unpaid_leave->user_id] = (int)$unpaid_leave->unpaid_count;
        }

        return $userCountMap;
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
