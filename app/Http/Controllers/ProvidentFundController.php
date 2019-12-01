<?php

namespace App\Http\Controllers;

use App\ProvidentFund;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;

class ProvidentFundController extends Controller
{
    use CustomsErrorsTrait;
    // if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('1');

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        //
    }

    public function store()
    {
        //
    }

    public function show()
    {
        //
    }

    public function update()
    {
        //
    }

    private function validateProvidentFund()
    {
        return request()->validate ([
            'user_id' => 'required|string',
        ]);
    }
}

/*
$table->bigIncrements('id');
$table->integer('user_id');
$table->string('month');
$table->integer('year');
$table->integer('month_count');
$table->double('opening_balance');
$table->double('deposit_amount_on_first_fifteen_days')->default(0);
$table->timestamp('deposit_date_on_first_fifteen_days')->nullable();
$table->double('deposit_amount_on_second_fifteen_days')->default(0);
$table->timestamp('deposit_date_on_second_fifteen_days')->nullable();
$table->double('withdraw_amount_on_first_fifteen_days')->default(0);
$table->timestamp('withdraw_date_on_first_fifteen_days')->nullable();
$table->double('withdraw_amount_on_second_fifteen_days')->default(0);
$table->timestamp('withdraw_date_on_second_fifteen_days')->nullable();
$table->double('lowest_balance')->default(0);
$table->double('rate');
$table->double('interest_for_this_month')->default(0);
$table->double('closing_balance')->default(0);
$table->timestamps();
*/
