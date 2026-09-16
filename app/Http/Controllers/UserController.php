<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login()
    {
        return view('user.login');
    }

    public function signup()
    {
        return view('user.signup');
    }

    public function create(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]); 
        
        
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

         return redirect()->route('login')->with('success', 'User created successfully.');
    }
    
    public function find(Request $request){
        $remember = $request->boolean('remember');
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

         if (Auth::attempt([
        'email' => $data['email'],
        'password' => $data['password'],
        ], $remember)) {
        $request->session()->regenerate();
        return redirect('home');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ]);
        
       
    }
}