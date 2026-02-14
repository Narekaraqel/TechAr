<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Data; // Твоя модель с данными датчиков
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller{
    public function dashboard(){
        $users = User::where('state_admin', 0)->get();
        $total = $users->count();
        return view('admin.admin_home', compact('users', 'total'));
    }

    public function createUser(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'state_admin' => 0 
        ]);
        return back()->with('success', 'Пользователь успешно добавлен!');
    }

    public function viewUserDetails($id){
        $user = User::findOrFail($id);
        $latestData = Data::where('user_id', $user->id)->latest()->first();
        return view('admin.user_details', compact('user', 'latestData'));
    }
}