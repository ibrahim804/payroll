<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;

class CompanyController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Can\'t store company information');

        $validate_attributes = $this->validateCompany();
        $company = Company::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'company' => $company,
            ]
        ];

    }

    public function show($id)
    {
        $company = Company::findOrFail($id);

        return
        [
            [
                'status' => 'OK',
                'name' => $company->name,
                'email' => $company->email,
                'address' => $company->address,
                'country' => $company->country,
                'phone' => $company->phone,
                'mobile' => $company->mobile,
                'website' => $company->website,
                'working_days' => $company->working_day,
            ]
        ];
    }

    public function update(Request $request, Company $company)
    {
        //
    }

    public function destroy(Company $company)
    {
        //
    }

    private function validateCompany()
    {
        return request()->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'address' => 'required|string|min:15|max:300',
            'country' => 'required|string',
            'phone' => 'string',
            'mobile' => 'string',
            'website' => 'string',
            'working_day_id' => 'required|string',
        ]);
    }
}






//
