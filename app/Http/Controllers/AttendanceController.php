<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index($month, $day)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied for normal user');

        $employees = Attendance::where([
            ['day', $day],
            ['month', $month],
        ])->get();

        return
        [
            [
                'status' => 'OK',
                '$users' => $employees,
            ]
        ];
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
        ]);

        // date("Y-m-d H:i:s", strtotime('+6 hours'))

        // $attendance->entry_time = Carbon::parse($attendance->created_at->addHours(+6))->format('Y-m-d H:i:s');
        // $attendance->save();

        return
        [
            [
                'status' => 'OK',
                'message' => 'Attendance created successfully, but entry time must be updated later.',
            ]
        ];
    }

    public function show($month, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied for normal user');

        $attendance = Attendance::where([
            ['user_id', $id],
            ['month', $month],
        ])->get();

        return
        [
            [
                'status' => 'OK',
                'attendances' => $attendance,
            ]
        ];
    }

    public function update(Request $request)
    {
        $attendance = auth()->user()->attendances->where('day', date("d", strtotime('+6 hours')))->first();

        if($attendance)
        {
            // $attendance->updatable_flag++;
            // $attendance->save();
            // $attendance->update(['exit_time' => Carbon::parse($attendance->updated_at->addHours(+6))->format('Y-m-d H:i:s')]);

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
}
