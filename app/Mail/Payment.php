<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\SharedTrait;

class Payment extends Mailable
{
    use Queueable, SerializesModels;
    use SharedTrait;

    public $payment, $company_name, $full_name, $month, $year;
    public $basic, $house, $medical, $fuel, $phone, $special, $other_a, $tax, $pf, $other_d, $gross, $total_d, $net, $unpaid, $leave_d, $payable;

    public function __construct($payment, $unpaid_leave_count, $company_name)
    {
        $this->payment = $payment;
        $this->company_name = $company_name;
        $this->full_name = $this->payment->user->full_name;
        $this->month = $this->payment->month;
        $this->year = $this->payment->year;
        $this->unpaid = $unpaid_leave_count;

        $this->setOtherVariables();
    }

    private function setOtherVariables()
    {
        $salary = $this->payment->user->salary;

        $this->basic = $salary->basic_salary;
        $this->house = ($salary->house_rent_allowance) ? $salary->house_rent_allowance : 0;
        $this->medical = ($salary->medical_allowance) ? $salary->medical_allowance : 0;
        $this->fuel = ($salary->fuel_allowance) ? $salary->fuel_allowance : 0;
        $this->phone = ($salary->phone_bill_allowance) ? $salary->phone_bill_allowance : 0;
        $this->special = ($salary->special_allowance) ? $salary->special_allowance : 0;
        $this->other_a = ($salary->other_allowance) ? $salary->other_allowance : 0;
        $this->tax = ($salary->tax_deduction) ? $salary->tax_deduction : 0;
        $this->pf = ($salary->provident_fund) ? $salary->provident_fund : 0;
        $this->other_d = ($salary->other_deduction) ? $salary->other_deduction : 0;

        $amounts = $this->calculatePayableAmount($salary);

        $this->gross = $amounts['gross_salary'];
        $this->total_d = $amounts['total_deduction'];
        $this->net = $amounts['net_salary'];

        $this->leave_d = $this->payment->employee_monthly_cost - $this->total_d;
        $this->payable = $this->payment->payable_amount;
    }

    public function build()
    {
        return $this->from(config('mail.username'), $this->company_name)
                    ->subject('Your Payment On'.' '.$this->month.' '.$this->year)
                    ->markdown('mail.payment');     // forgot-password is mail template under mail Directory.
    }
}











//
