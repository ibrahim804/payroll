<?php

namespace App\Http\Controllers;

use App\Working_day;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;

class WorkingDayController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = $this->validateWorkingDay();
        $working_day = Working_day::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'working_day_id' => $working_day->id,
                'working_days' => $working_day,
            ]
        ];
    }

    public function show(Working_day $working_day)
    {
        //
    }

    public function update(Request $request, Working_day $working_day)
    {
        //
    }

    public function destroy(Working_day $working_day)
    {
        //
    }

    private function validateWorkingDay()
    {
        return request()->validate([
            'saturday' => 'required|string',
            'sunday' => 'required|string',
            'monday' => 'required|string',
            'tuesday' => 'required|string',
            'wednesday' => 'required|string',
            'thursday' => 'required|string',
            'friday' => 'required|string',
        ]);
    }
}








//
