<?php

namespace App\Http\Controllers;

use App\Working_day;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\User;
use App\Company;

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

        if($validate_attributes['user_or_company'] == 'user')
        {
            $requested_user = User::findOrFail($validate_attributes['user_id']);

            if($requested_user->working_day_id) return $this->getErrorMessage('This user already has a working day.');

            $working_day = Working_day::create($validate_attributes);
            $requested_user->update(['working_day_id' => $working_day->id]);
        }

        else if($validate_attributes['user_or_company'] == 'company')
        {
            $requested_company = Company::findOrFail($validate_attributes['company_id']);

            if($requested_company->working_day_id) return $this->getErrorMessage('This company already has a working day.');

            $working_day = Working_day::create($validate_attributes);
            $requested_company->update(['working_day_id' => $working_day->id]);
        }

        else
        {
            return $this->getErrorMessage('Only company and user can be working day');
        }

        return
        [
            [
                'status' => 'OK',
                'working_day_id' => $working_day->id,
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
            $user_or_company = request()->validate(['user_or_company' => 'required|string']);

            if($user_or_company['user_or_company'] == 'user')
            {
                $validate_attributes['user_or_company'] = 'user';
                $tempArray = request()->validate(['user_id' => 'required|string']);
                $validate_attributes['user_id'] = $tempArray['user_id'];
            }

            else if($user_or_company['user_or_company'] == 'company')
            {
                $validate_attributes['user_or_company'] = 'company';
                $tempArray = request()->validate(['company_id' => 'required|string']);
                $validate_attributes['company_id'] = $tempArray['company_id'];
            }

            else
            {
                $validate_attributes['user_or_company'] = 'nothing'; // for error handling
            }
        }

        return $validate_attributes;
    }
}








//
