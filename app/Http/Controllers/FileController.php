<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
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
