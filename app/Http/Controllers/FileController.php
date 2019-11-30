<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\User;
use App\MyErrorObject;

class FileController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->middleware('auth:api')->except(['create_user']);
    }

    public function create_user(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to register user through file uploading');

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
                if (!$header)
                {
                    // $header = $row;
                    $header = array('full_name', 'email', 'password', 'gender', 'phone', 'joining_date');
                }

                else
                {
                    $single_row = array_combine($header, $row);
                    $validator = $this->validateUser($single_row);

                    if ($validator->fails())
                    {
                        $myCustomErrors[] = new MyErrorObject;
                        $myCustomErrors[$error_index]->id = $row_count;
                        $myCustomErrors[$error_index]->error = $validator->errors();
                        $myCustomErrors[$error_index]->profile_pictures = NULL;
                        $myCustomErrors[$error_index]->trashed_pictures = NULL;
                        $error_index++;
                    }

                    else
                    {
                        $single_row['password'] = bcrypt($single_row['password']);
                        User::create($single_row);
                    }
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

    public function setProfilePicture(Request $request)
    {
        $validate_attributes = request()->validate(['image' => 'required|image|max:2048']); // can't greater than 2 mb
        $myObject = new MyErrorObject;

        $image = $request->file('image');
        $image_name = auth()->id().'.'.bin2hex(random_bytes(8)).'.'.$image->getClientOriginalExtension();
        $image->move(public_path($myObject->profile_pictures), $image_name);

        $image_path = $myObject->profile_pictures.'/'.$image_name;
        auth()->user()->update(['photo_path' => $image_path]);

        return
        [
            [
                'status' => 'OK',
                'image_path' => url($image_path),
            ]
        ];
    }

    public function getProfilePicture()
    {
        $image_path = public_path(auth()->user()->photo_path);
        $base64String = base64_encode(file_get_contents($image_path));

        return
        [
            [
                'status' => 'OK',
                'base64' => $base64String,
            ]
        ];
    }

}















//
