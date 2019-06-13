<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

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
        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'date' => date('Y-m-d'),
            'day' => date('d'),
            'month' => date('M'),
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
