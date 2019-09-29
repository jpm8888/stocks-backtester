<?php
/**
 * User: jpm
 * Date: 07/08/18
 * Time: 11:28 AM
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ControllerChangePassword extends Controller {

    public function index() {
        return view('auth.change_password');
    }

    public function update(Request $req) {
        $rules = [
            'current' => 'required|min:6',
            'new' => 'min:6|required',
            'confirm_new' => 'required|same:new'
        ];

        $validator = Validator::make(Input::all(), $rules, $this->messages());

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $data = $req->all();
        $user = User::where('id', auth()->user()->id)->first();
        if (!Hash::check($data['current'], $user->password)) {
            $validator->getMessageBag()->add('current', 'The specified password does not match the database password');
            return Redirect::back()->withErrors($validator);
        } else {
            $user->password = Hash::make($data['new']);
            $user->save();
            return Redirect::back()->with('success', 'Password updated successfully');
        }

    }


    public function messages() {
        return [
            'current.min' => 'The password must be at least 6 characters.',
            'new.min' => 'The password must be at least 6 characters.',
            'confirm_new.same' => 'Passwords Mismatch',
        ];
    }

}


