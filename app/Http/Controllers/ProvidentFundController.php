<?php

namespace App\Http\Controllers;

use App\ProvidentFund;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use Carbon\Carbon;
use App\User;
use App\MyErrorObject;

class ProvidentFundController extends Controller
{
    use CustomsErrorsTrait;

    private $myObject;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
        $this->myObject = new MyErrorObject;
    }

    // public function index()
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');
    //
    //     $all_pf = ProvidentFund::all();
    //     $provident_funds = [];
    //
    //     for($index = sizeof($all_pf)-1; $index >= 0; $index--)
    //     {
    //         // code
    //     }
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'provident_funds' => $provident_funds,
    //         ]
    //     ];
    // }

    public function store()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = $this->validateProvidentFund();
        $user = User::find($validate_attributes['user_id']);

        if(! $user) return $this->getErrorMessage('User doesn\'t exist');

        $running_month = date("M", strtotime('+6 hours'));
        $running_year  = date("Y", strtotime('+6 hours'));
        $isExist = ProvidentFund::where([
            ['user_id', $validate_attributes['user_id']],
            ['month', $running_month],
            ['year', $running_year],
        ])->count();

        if($isExist > 0) return $this->getErrorMessage('PF for this month already exists');

        $validate_attributes['month'] = $running_month;
        $validate_attributes['year']  = $running_year;

        $last_pf = ProvidentFund::where('user_id', $validate_attributes['user_id'])->latest()->first();

        $validate_attributes['opening_balance'] = ($last_pf) ? $last_pf->closing_balance : 0;
        $validate_attributes['basic_salary'] = $user->salary->basic_salary;
        $validate_attributes['deposit_rate'] = $this->myObject->deposit_rate;
        $validate_attributes['deposit_balance'] = $validate_attributes['basic_salary'] * $validate_attributes['deposit_rate'];
        $validate_attributes['pf_yearly_rate'] = $this->myObject->pf_yearly_rate;
        $validate_attributes['closing_balance'] =
            $this->calculateClosingBalance($validate_attributes['opening_balance'], $validate_attributes['deposit_balance']);

        $provident_fund = ProvidentFund::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'provident_fund' => $provident_fund,
            ]
        ];
    }

    public function show($user_id)
    {
        if(auth()->id() != $user_id) return $this->getErrorMessage('Employee can check only his provident_fund');

        $provident_fund = ProvidentFund::where('user_id', $user_id)->latest()->first();

        return
        [
            [
                'status' => 'OK',
                'provident_fund' => $provident_fund,
            ]
        ];
    }

    private function validateProvidentFund()
    {
        return request()->validate ([
            'user_id' => 'required|string',
        ]);
    }

    private function calculateClosingBalance($opening_banalce, $deposit_balance)
    {
        $without_interest = $opening_banalce + $deposit_balance;
        $interest = $without_interest * ($this->myObject->pf_yearly_rate / 12.0);
        return $without_interest + $interest;
    }
}

////














//
