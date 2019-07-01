<?php

namespace App\Http\Controllers;

use App\LeaveCount;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;

class LeaveCountController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(LeaveCount $leaveCount)
    {
        //
    }

    public function update(Request $request, LeaveCount $leaveCount)
    {
        //
    }

    public function destroy(LeaveCount $leaveCount)
    {
        //
    }
}
