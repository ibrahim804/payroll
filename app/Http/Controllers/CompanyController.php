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

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Access Denied');

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

    public function show(Company $company)
    {
        //
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
        ]);
    }
}






//
