<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Designation;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomsErrorsTrait;
use Validator;
use File;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserVerification;
use Carbon\Carbon;

class UserController extends Controller
{

    /*
        CustomsErrorsTrait is a trait which contains some methods. Evety Controller that uses CustomsErrorsTrait can access all it's methods.
         To access some methods globally, we generally create a trait and write function there.
    */

    use CustomsErrorsTrait;

    public $successStatus = 200;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'forgot_password', 'verifyVerificationCode', 'setNewPasswordAfterUserVerification']);

        /* To allow some specific methods instead, we use "only".
            For example: $this->middleware('auth:api')->only(['index', logout]);
            Both only and except can't be used within a same Controller.
        */
    }

    /*
        returns all users' id, full_name, payable salary, company name, department, designation, casual_leave left, sick_leave left, photo_path,
    */

    public function index(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied');

        $users = User::all();               // fetches all users
        $i = 0; $infos = [];

        foreach($users as $user) {

            $infos[$i] = new User;

            $infos[$i]->id = $user->id;
            $infos[$i]->full_name = $user->full_name;
            $infos[$i]->salary = ($user->salary) ? $this->calculateNetSalary($user->salary) : 'N/A';
            $infos[$i]->company = ($user->company) ? $user->company->name : 'N/A';
            $infos[$i]->department = ($user->department) ? $user->department->department_name : 'N/A';
            $infos[$i]->designation = ($user->designation) ? $user->designation->designation : 'N/A';
            $infos[$i]->casual_leave = $user->leave_counts->where('leave_category_id', 1)->first()->leave_left;
            $infos[$i]->sick_leave = $user->leave_counts->where('leave_category_id', 2)->first()->leave_left;
            $infos[$i]->photo_path = url($user->photo_path);

            $i++;
        }

        return
        [
            [
                'status' => 'OK',
                'users' => $infos,
            ]
        ];
    }

    /*
        Takes salary object(record) and retrurns payable amount
    */

    private function calculateNetSalary($salary)
    {
        return $salary['basic_salary']
            +  $salary['house_rent_allowance'] + $salary['medical_allowance']
            +  $salary['special_allowance'] + $salary['fuel_allowance']
            +  $salary['phone_bill_allowance'] + $salary['other_allowance']
            -  $salary['tax_deduction'] -  $salary['provident_fund'] - $salary['other_deduction'];
    }

    /*
        Input: email and password
        returns full_name, email and token.
    */

    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user();   // user is now logged in
            $success['token'] = $user->createToken(config('app.name'))->accessToken;    // create a token

            return
            [
                [
                    'status' => 'OK',
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'token' => $success['token'],
                ]
            ];
        }

        /*
            getErrorMessage is a method under CustomsErrorsTrait. As, this Controller uses CustomsErrorsTrait, it get's access of this method.
        */

        return $this->getErrorMessage('credentials are not matched.');

    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();      // The token is now no longer available.

        return
        [
            [
                'status' => 'OK',
                'message' => 'Logged Out Successfully',
            ]
        ];
    }

    public function register(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Only admin can create user');

        $validator = $this->validateUser($request->all());          // validateUser is a method of CustomsErrorsTrait. Please check

        if ($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());    // getErrorMessage is a method of CustomsErrorsTrait
        }

        /*
            About user creation:
                All parameters that admin fills out are not required. It must be checked whether not-required fields are filled out or not.
        */

        $user = User::create($this->getProcessedInputsWhileCreatingUser($request));
        $success['token'] = $user->createToken(config('app.name'))->accessToken;    // syntex

        return
        [
            [
                'status' => 'OK',
                'full_name' => $user->full_name,
                'email' => $user->email,
                'token' => $success['token'],
            ]
        ];
    }

    /*
        User can view only her/his user onfo, none else. But admin can
    */

    public function user($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false' and auth()->id() != $id)
        {
            return $this->getErrorMessage('You don\'t have permission to view any user info');
        }

        $user = User::find($id);              // if not found, returns an exception

        return
        [
            [
                'status' => 'OK',
                'description' => $user,
            ]
        ];
    }

    /*
        Specific user can update her/his info. But admin can anyone's.
    */

    public function update(Request $request, $id)
    {
        $user = Auth::user();                       // catch the user

        if($user->isAdmin($user->id) == 'false' and $user->id != $id) return $this->getErrorMessage('Permission denied');

        if($user->id == $id)
        {
            $validate_attributes = request()->validate([
                'full_name' => 'string|min:3|max:25',
                'user_name' => 'string|min:3|max:25|unique:users',
                'email' => 'string|email|max:255|unique:users',
                'date_of_birth' => 'date',
                'fathers_name' => 'string|min:3|max:25',
                'gender' => 'string',
                'marital_status' => 'string',
                'nationality' => 'string',
                'permanent_address' => 'string|min:10|max:300',
                'present_address' => 'string|min:10|max:300',
                'passport_number' => 'string',
                'phone' => 'string',
            ]);

            $user->update($validate_attributes);
        }

        if($user->isAdmin($user->id) == 'true')
        {
            $extra_attributes = request()->validate([
                'employee_id' => 'string',
                'company_id' => 'string',
                'designation_id' => 'string',
                'department_id' => 'string',
                'salary_id' => 'string',
                'joining_date' => 'date',
            ]);

            $user_to_be_updated = User::find($id);

            if($extra_attributes['designation_id'])
            {
                $designation = Designation::find($extra_attributes['designation_id']);
                if($designation) $extra_attributes['department_id'] = $designation->department_id;
                else $extra_attributes['designation_id'] = NUll;
            }

            $user_to_be_updated->update($extra_attributes);
        }

        return
        [
            [
                'status' => 'OK',
                'message' => 'Info Updated',
            ]
        ];
    }

    /*
        For not-required parameters: If any parameter is filled out, it will be stored.
    */

    private function getProcessedInputsWhileCreatingUser(Request $request)
    {
        $inputs = [];

        /*
            We can insert values in $inputs array in the following way,

            $inputs = $request->all();

            But the problem is, some unexpected parameters can be passed. But out list is specific.
        */

        $inputs['full_name'] = $request->input('full_name');
        $inputs['email'] = $request->input('email');
        $inputs['password'] = bcrypt($request->input('password'));
        $inputs['gender'] = $request->input('gender');
        $inputs['phone'] = $request->input('phone');
        $inputs['joining_date'] = $request->input('joining_date');

        if($request->filled('employee_id')) $inputs['employee_id'] = $request->input('employee_id');
        if($request->filled('user_name')) $inputs['user_name'] = $request->input('user_name');
        if($request->filled('date_of_birth')) $inputs['date_of_birth'] = $request->input('date_of_birth');
        if($request->filled('fathers_name')) $inputs['fathers_name'] = $request->input('fathers_name');
        if($request->filled('marital_status')) $inputs['marital_status'] = $request->input('marital_status');
        if($request->filled('nationality')) $inputs['nationality'] = $request->input('nationality');
        if($request->filled('permanent_address')) $inputs['permanent_address'] = $request->input('permanent_address');
        if($request->filled('present_address')) $inputs['present_address'] = $request->input('present_address');
        if($request->filled('passport_number')) $inputs['passport_number'] = $request->input('passport_number');
        if($request->filled('company_id')) $inputs['company_id'] = $request->input('company_id');
        if($request->filled('department_id')) $inputs['department_id'] = $request->input('department_id');
        if($request->filled('designation_id')) $inputs['designation_id'] = $request->input('designation_id');

        return $inputs;
    }

    /*
        The following route will be called when user knows her/his password but wants to update
    */

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());
        }

        if(Auth::guard('web')->attempt(['id' => auth()->id(), 'password' => $request->input('current_password')]))
        {
            $new_password = bcrypt($request->input('new_password'));        // new_password should be hashed beforing storing
            auth()->user()->update(['password' => $new_password]);

            return
            [
                [
                    'status' => 'OK',
                    'message' => 'Password has been changed successfully',
                ]
            ];
        }

        return $this->getErrorMessage('Current password is not correct.');
    }

    /*
        Hits when user forgets her/his password. User must provide her/his email to get mail.
        1. System sends a verification code to user's mail.
        2. Store that verification code to user's database for later verification
    */

    public function forgot_password(Request $request)
    {
        $email = $request->input('email');
        if(!$email) return $this->getErrorMessage('Please, enter your email');

        $user = User::where('email', $email)->first();

        if(! $user) return $this->getErrorMessage('User with this email doesn\'t exist');

        $verification_code = mt_rand(100000, 999999);           // generate a 6 digit code

        Mail::to($email)->send(
            new UserVerification($verification_code, $user->full_name, ($user->company) ? $user->company->name : '')
        );

        $user->update(['verification_code' => $verification_code]);     // verification_code is stored in user database for verify this user later

        return
        [
            [
                'status' => 'OK',
                'message' => 'A verification code is sent to your email, Check your inbox now.',
            ]
        ];
    }

    /*
        Following route will be called after receiving verification code
        Input: take email for querying verification code from database

        Then user submit code here what s/he received to set new password. The field for verification code of database can be,
        1. NULL (reasons are: code is not sent successfully through mail, or code gets expired already. Note that, code gets expired when it is one minute old)
        2. 6 digit number (Code is sent to mail, but not verified yet.)
        3. 1 (When user is already verified)

        Output: Ensures that user is now allowed to set a new password
    */

    public function verifyVerificationCode(Request $request)
    {
        $email = $request->input('email');
        $verification_code = $request->input('verification_code');

        if(!$email or !$verification_code)
        {
          return $this->getErrorMessage('Email and Verification code must be given');
        }

        $user = User::where('email', $email)->first();

        if(! $user) return $this->getErrorMessage('User with this email doesn\'t exist');

        if($user->verification_code == 1)
        {
            return
            [
                [
                    'status' => 'OK',
                    'message' => 'Verification code already verified, you can set your password now.',
                ]
            ];
        }

        $user_updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $user->updated_at);          // when verification code is created.
        $current_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
        $ageOfVerificationCode = $user_updated_at->diffInSeconds($current_time);                // get the age of verification code

        if($ageOfVerificationCode >= 120) $user->update(['verification_code' => NULL]);          // verification code get expired after one minute

        if(! $user->verification_code) return $this->getErrorMessage('Either you didn\'t send forgot password request, or your code expired');
        if($verification_code != $user->verification_code) return $this->getErrorMessage('Verification code isn\'t matched');

        $user->update(['verification_code' => 1]);                                              // OK, user is not verified and allowed to set new password

        return
        [
            [
                'status' => 'OK',
                'message' => 'Verification code verified successfully.',
            ]
        ];

    }

    /*
        The following route will be called when user is already verified through verification acode and ready to set a new password
        Input: email, new_password and confirm_password
    */

    public function setNewPasswordAfterUserVerification(Request $request)
    {
        $validate_attributes = request()->validate([            // these validations are not working for being out of auth:api. I don't know why. Check it later.
            'email' => 'required|string',
            'new_password' => 'required|string|min:6|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = User::where('email', $validate_attributes['email'])->first();

        if(! $user) return $this->getErrorMessage('User with this email doesn\'t exist');
        if($user->verification_code != 1) return $this->getErrorMessage('Please verify your verification code firstly to set new password'); // No permission if code is not verified yet.

        $user->update([
            'password' => bcrypt($validate_attributes['new_password']),             // password is hashed
            'verification_code' => NULL,
        ]);

        return
        [
            [
                'status' => 'OK',
                'message' => 'User password reset successfully.',
            ]
        ];
    }

    // SoftDelete

    public function delete(Request $request, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied for normal user');

        User::find($id)->delete();

        return
        [
            [
                'status' => 'OK',
                'message' => 'User deleted successfully',
            ]
        ];
    }

    public function restore(Request $request, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied for normal user');

        User::onlyTrashed()->where('id', $id)->restore();                       // Deleted user can be restored when it is trashed.

        return
        [
            [
                'status' => 'OK',
                'message' => 'User restored successfully.',
            ]
        ];
    }

    /* If user wants to remove her/his profile picture
        Their are two directories /public/images. one is profile_pictures(all profile pictures are here), another is trashed_pictures(all removed profile pictures are here)
        So when user remove photo, the system basically move profile picture from profile_pictures directory to trashed_pictures directory
    */

    public function remove_photo($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false' and auth()->id() != $id)    // User can remove her/his profile picture, Admin can anyone's
        {
            return $this->getErrorMessage('Permission denied.');
        }

        $user = User::find($id);

        if(!$user->photo_path) return $this->getErrorMessage('No photo has been found in this path.');      // If photo exists, path must have a value

        $actual_old_path = public_path($user->photo_path);              // physical path where the photo is
        $extension = pathinfo($actual_old_path, PATHINFO_EXTENSION);    // extension is needed to rename that profile picture in trashed_pictures directory,
        $actual_new_path = public_path().(new \App\MyErrorObject)->trashed_pictures.'/'.bin2hex(random_bytes(8)).'.'.$extension;        // where to save(trashed_pictures location)

        /* Another way of finding extension from path string
            $infoPath = pathinfo(public_path('/uploads/my_image.jpg'));
            $extension = $infoPath['extension'];
        */

        if(! File::exists($actual_old_path)) return $this->getErrorMessage('File doesn\'t exists in this path');    // before moving, we need to ensure that, photo really exists in this actual path

        File::move($actual_old_path, $actual_new_path);     // profile_pictures to trashed_pictures
        $user->update(['photo_path' => NULL]);              // No photo in profile_pictures directory

        return
        [
            [
                'status' => 'OK',
                'message' => 'Image removed successfully',
            ]
        ];
    }
}











//
