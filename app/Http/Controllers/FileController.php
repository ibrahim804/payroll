<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;

class FileController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function upload(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to upload file');

        $validate_attributes = request()->validate(['file' => 'required|file|max:5120']);

        $file_name = $request->file('file')->getClientOriginalName();
        $file_path = url('/files'.'/'.$file_name);

        $request->file('file')->move(public_path('/files'), $file_name);

        return
        [
            [
                'status' => 'OK',
                'url' => $file_path,
            ]
        ];
    }
}
