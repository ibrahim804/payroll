<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;

class AttendanceController extends Controller
{

    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {

        $count = auth()->user()->attendances->where('day', date("d", strtotime('+6 hours')))->count();

        if($count > 0) return $this->getErrorMessage('Today\'s attendance for this user already exists.');

        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'date' => date("Y-m-d", strtotime('+6 hours')),
            'day' => date("d", strtotime('+6 hours')),
            'month' => date("M", strtotime('+6 hours')),
            'entry_time' => date("Y-m-d H:i:s", strtotime('+6 hours')),
            'exit_time' => date("Y-m-d H:i:s", strtotime('+6 hours')),
        ]);

        return
        [
            [
                'status' => 'OK',
                'message' => 'Attendance created successfully, but entry time must be updated later.',
                'attendance' => $attendance,
            ]
        ];
    }

    public function show(Attendance $attendance)
    {
        //
    }

    public function update(Request $request)
    {
        $attendance = auth()->user()->attendances->where('day', date("d", strtotime('+6 hours')))->first();

        if($attendance)
        {
            $attendance->update(['exit_time' => date("Y-m-d H:i:s", strtotime('+6 hours'))]);

            return
            [
                [
                    'status' => 'OK',
                    'message' => 'Attendance exit time gets stored successfully.',
                ]
            ];
        }

        return $this->getErrorMessage('User has no attendance for Today');
    }

    public function destroy(Attendance $attendance)
    {
        //
    }

    // private function validateAttendance()
    // {
    //     return request()->validate([                           // For DATABASE Validation
    //         'game_id' => 'required',
    //         'activation_date'  => 'required',
    //         'text' => 'required'
    //     ]);
    // }
}
