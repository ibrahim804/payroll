<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;

class RoleController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission Denied');

        $roles = Role::where('type', '<>', 'admin')->get();

        return
        [
            [
                'status' => 'OK',
                'roles' => $roles,
            ]
        ];
    }

    public function leaders()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied');

        $leader_id = Role::where('type', 'leader')->first()->id;
        $leaders = User::select('id', 'full_name')->where('role_id', $leader_id)->get();

        return
        [
            [
                'status' => 'OK',
                'leaders' => $leaders,
            ]
        ];
    }
}
