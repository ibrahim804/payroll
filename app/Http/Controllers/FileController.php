<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\User;
use Validator;
use App\MyErrorObject;

class FileController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function upload(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to upload file');

        $validator = request()->validate(['file' => 'required|file']); // required|file|max:5120, example of multiple mimes:csv,txt
        $file = $request->file('file');

        if($file->getClientOriginalExtension() != 'csv')
        {
            return $this->getErrorMessage('you uploaded a '.$file->getClientOriginalExtension().' file, only csv is allowed.');
        }

        /*
        IF WE WANT TO STORE THE REQUESTED FILE TO PUBLIC FOLDER.

        $file_name = $file->getClientOriginalName();
        $file_path = url('/files'.'/'.$file_name);
        $request->file('file')->move(public_path('/files'), $file_name);

        // or to retrieve the file from public folder,     $file = public_path('files/test.csv');
        */

        return $this->csvToArrayToDatabase($file);
    }

    private function csvToArrayToDatabase($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) return $this->getErrorMessage('File is not readable.');

        $header = null; $row_count = 1; $error_index = 0; $myCustomErrors = [];

        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header) $header = $row;

                else
                {
                    $single_row = array_combine($header, $row);
                    $validator = $this->validateUser($single_row);

                    if ($validator->fails())
                    {
                        $myCustomErrors[] = new MyErrorObject;
                        $myCustomErrors[$error_index]->id = $row_count;
                        $myCustomErrors[$error_index]->error = $validator->errors();
                        $error_index++;
                    }

                    else User::create($single_row);
                }

                $row_count++;
            }

            fclose($handle);

            return
            [
                [
                    'status' => 'OK',
                    'message' => 'User from uploaded file created successfully.',
                    'errors' => $myCustomErrors,
                ]
            ];
        }

        return $this->getErrorMessage('File can\'t be open.');
    }

    private function validateUser($single_row)
    {
        return Validator::make($single_row, [
            'employee_id' => 'string',
            'full_name' => 'required|string|min:3|max:25',
            'user_name' => 'string|min:3|max:25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:30',
            'date_of_birth' => 'date',
            'fathers_name' => 'string|min:3|max:25',
            'gender' => 'required|string',
            'marital_status' => 'string',
            'nationality' => 'string',
            'permanent_address' => 'string|min:10|max:300',
            'present_address' => 'string|min:10|max:300',
            'passport_number' => 'string',
            'phone' => 'required|string',
            'designation_id' => 'string',
            'department_id' => 'string',
            'joining_date' => 'required|date',
        ]);
    }
}
