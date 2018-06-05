<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password'), 'confirmToken' => ''])){
            return redirect('/home');
        }
        elseif (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')]) == false){
            return redirect('/login')->with('wrongAuthData', 'Не съществува потребител с такъв е-майл или парола.');
        }
        else{
            return redirect('/must-confirm');
        }
    }
}
