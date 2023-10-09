<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required'
        ])->validate();

        if(!Auth::attempt($request->only('name','password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'name' => trans('auth.failed')
            ]);
        }

        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }

    public function profile()
    {
         return view('profile');
    }
}
