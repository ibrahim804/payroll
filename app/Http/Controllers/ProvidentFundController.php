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

//














//
