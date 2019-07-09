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
        // $this->middleware('auth:api');
    }

    public function create_user(Request $request)
    {
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

    public function setProfilePicture(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validate_attributes = request()->validate(['image' => 'required|image|max:2048']); // can't greater than 2 mb
        $myObject = new MyErrorObject;

        $image = $request->file('image');
        $image_name = $id.'.'.bin2hex(random_bytes(8)).'.'.$image->getClientOriginalExtension();
        $image->move(public_path($myObject->profile_pictures), $image_name);

        $image_path = $myObject->profile_pictures.'/'.$image_name;
        $user->update(['photo_path' => $image_path]);

        return
        [
            [
                'status' => 'OK',
                'image_path' => url($image_path),
            ]
        ];
    }

}















//
