<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConfirmController extends Controller
{
    protected $redirectTo = '/home';

    public function index($confirmToken='')
    {
        $data = User::where('confirmToken', $confirmToken)->update(['confirmToken' => '']);
        if($data){
            return redirect('/login');
        }
    }

    public function mustConfirm()
    {
        if(Auth::check()){

            Session::flush();

            return redirect('/must-confirm');
        }

        return view('errors.must-confirm');
    }
}
