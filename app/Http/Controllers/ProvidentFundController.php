<?php

namespace App\Http\Controllers;

use App\ProvidentFund;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\Http\Controllers\SharedTrait;
use Carbon\Carbon;
use App\User;
use App\MyErrorObject;

class ProvidentFundController extends Controller
{
    use CustomsErrorsTrait, SharedTrait;

    private $myObject;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
        $this->myObject = new MyErrorObject;
    }

    public function store()
    {
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = $this->validateProvidentFund();

        $user = User::find($validate_attributes['user_id']);

        if(! $user) return $this->getErrorMessage('User doesn\'t exist');

        if($user->deposit_pf == 0) return $this->getErrorMessage('PF calculation for this user is not applicalbe');

        $running_month = date("M", strtotime('+6 hours'));
        $running_year  = date("Y", strtotime('+6 hours'));
        $isExist = ProvidentFund::where([
            ['user_id', $validate_attributes['user_id']],
            ['month', $running_month],
            ['year', $running_year],
        ])->count();

        if($isExist > 0) return $this->getErrorMessage('PF for this user already exist');

        $validate_attributes['month'] = $running_month;
        $validate_attributes['year']  = $running_year;

        $last_pf = ProvidentFund::where('user_id', $validate_attributes['user_id'])->latest()->first();

        $validate_attributes['opening_balance'] = ($last_pf) ? $last_pf->closing_balance : 0;
        $validate_attributes['gross_salary'] = $this->calculateGross($user->salary);
        $validate_attributes['deposit_rate'] = $this->myObject->monthly_deposit_rate;
        $validate_attributes['deposit_balance'] = $validate_attributes['gross_salary'] * $validate_attributes['deposit_rate'];
        $validate_attributes['opening_and_deposit'] = $validate_attributes['opening_balance'] + $validate_attributes['deposit_balance'];
        $validate_attributes['payment_in_times'] = $user->payments->count();
        $validate_attributes['company_contribution_rate'] = $this->getCompanyContributionRate($validate_attributes['payment_in_times']);
        $validate_attributes['company_contribution'] = $validate_attributes['opening_and_deposit'] * $validate_attributes['company_contribution_rate'];
        $validate_attributes['closing_balance'] = $validate_attributes['opening_and_deposit'] + $validate_attributes['company_contribution'];

        $provident_fund = ProvidentFund::create($validate_attributes);

        return $this->showSuccessMessage('PF created successfully');
    }

    private function validateProvidentFund()
    {
        return request()->validate([
            'user_id' => 'required|string',
        ]);
    }

    private function getCompanyContributionRate($payment_in_times)
    {
        $active_years = (int)($payment_in_times / 12);

        if($active_years < 2) return 0;
        if($active_years >= 5) return 1;

        return 0.25 * ($active_years - 1);
    }
}

////














//
