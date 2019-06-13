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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
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
