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
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You are not allowed to show all companies information');

        $companies = Company::all();

        return
        [
            [
                'status' => 'OK',
                'companies' => $companies,
            ]
        ];
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Can\'t store company information');

        $validate_attributes = $this->validateCompany('store');
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

    public function update(Request $request, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You Can\'t update company information');

        $company = Company::findOrFail($id);
        $validate_attributes = $this->validateCompany('update');
        $company->update($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'company' => $company,
            ]
        ];
    }

    public function destroy($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to destroy Company');

        Company::findOrFail($id)->delete();

        return
        [
            [
                'status' => 'OK',
                'message' => 'Requested company deleted successfully',
            ]
        ];
    }

    public function restore($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to restore Company');

        Company::onlyTrashed()->where('id', $id)->restore();

        return
        [
            [
                'status' => 'OK',
                'message' => 'Requested company is restored successfully.',
            ]
        ];
    }

    public function trashedIndex()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view deleted Companies');

        $companies = Company::onlyTrashed()->get();

        return
        [
            [
                'status' => 'OK',
                'companies' => $companies,
            ]
        ];
    }

    private function validateCompany(string $operation)
    {
        if($operation != 'store' and $operation != 'update') return $this->getErrorMessage('wrong param passed.');

        return request()->validate([
            'name' => ($operation == 'store') ? 'required|string|unique:companies' : 'string|unique:companies',
            'email' => ($operation == 'store') ? 'required|string|email' : 'string|email',
            'address' => ($operation == 'store') ? 'required|string|min:15|max:300' : 'string|min:15|max:300',
            'country' => ($operation == 'store') ? 'required|string' : 'string',
            'phone' => 'string',
            'mobile' => 'string',
            'website' => 'string',
        ]);
    }
}






//
