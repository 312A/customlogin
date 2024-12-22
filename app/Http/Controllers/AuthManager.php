<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
class AuthManager extends Controller
{
    public function login(){
        if(Auth::check()){
            return redirect(route('home'));
        }
        return view('login');
    }
    public function registration(){
        if(Auth::check()){
            return redirect(route('home'));
        }
        return view('registration');
    }

    public function loginPost(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            return  redirect()->intended(route('home'));
        }
        return redirect(route('login'))->with("error","Login Details are not valid");
    }
    public function registrationPost(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        if(!$user){
            return redirect(route('register'))->with("error","Registration Failed Try Again");
        }
        return redirect(route('login'))->with("success","Regisrtation success,Login to access the app");
    }
    public function logout(){
        session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
