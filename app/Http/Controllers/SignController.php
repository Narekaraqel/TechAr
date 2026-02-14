<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User; 

class SignController extends Controller{
    public function SignVerification(Request $req){
        $credentials = $req->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $req->session()->regenerate();
            return redirect('/home');
        }
        return back()->withErrors(['email' => 'Неверный логин или пароль']);
    }
    public function logout(Request $request) {
        Auth::user(); 
        Auth::logout(); 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken();
        return redirect()->route('login'); 
    }
}


