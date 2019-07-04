<?php

namespace App\Http\Controllers;

use App\Working_day;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\User;

class WorkingDayController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = $this->validateWorkingDay(1);
        $requested_user = User::findOrFail($validate_attributes['user_id']);

        if($requested_user->working_day_id) return $this->getErrorMessage('This user already has a working day.');

        $working_day = Working_day::create($validate_attributes);
        $requested_user->update(['working_day_id' => $working_day->id]);

        return
        [
            [
                'status' => 'OK',
                'working_day_id' => $working_day->id,
                'working_days' => $working_day,
            ]
        ];
    }

    public function show($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false' and auth()->id() != $id) return $this->getErrorMessage('Permission Denied');

        $working_day = User::findOrFail($id)->working_day;

        return
        [
            [
                'status' => 'OK',
                'working_day' => $working_day,
            ]
        ];
    }

    public function update($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Only admin can update one\'s working day info');

        $working_day = User::findOrFail($id)->working_day;
        $validate_attributes = $this->validateWorkingDay(0);
        $working_day->update($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'working_day' => $working_day,
            ]
        ];
    }

    private function validateWorkingDay(int $StoreOrUpdate) // 1 for store, 0 for update
    {
        $validate_attributes = request()->validate([
            'saturday' => ($StoreOrUpdate) ? 'required|string' : 'string',
            'sunday' => ($StoreOrUpdate) ? 'required|string' : 'string',
            'monday' => ($StoreOrUpdate) ? 'required|string' : 'string',
            'tuesday' => ($StoreOrUpdate) ? 'required|string' : 'string',
            'wednesday' => ($StoreOrUpdate) ? 'required|string' : 'string',
            'thursday' => ($StoreOrUpdate) ? 'required|string' : 'string',
            'friday' => ($StoreOrUpdate) ? 'required|string' : 'string',
        ]);

        if($StoreOrUpdate)
        {
            $tempArray = request()->validate(['user_id' => 'required|string']);
            $validate_attributes['user_id'] = $tempArray['user_id'];
        }

        return $validate_attributes;
    }
}








//
