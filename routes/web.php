<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\SignController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\DataController;
use App\Models\Data;
use Illuminate\Support\Carbon;
use App\Http\Controllers\AdminController;



Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/sign', function () {
        return view('sign');
    })->name('login');
    Route::post('/sign/verification', [SignController::class, 'SignVerification'])->name('sign-verification');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [SignController::class, 'logout'])->name('logout');
    Route::post('/update-rele', [DataController::class, 'updateRele'])->name('rele.update');
    Route::post('/get-history', [DataController::class, 'getHistory'])->name('data.history');

    Route::get('/home', function () {
        $user = Auth::user();
        if ($user->state_admin == 1) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.profile', [
            'name' => Str::slug($user->name), 
            'id' => $user->id
        ]);
    });

    Route::get('/user/{name}/{id}', function ($name, $id) {
        $user = Auth::user();
        if ($user->id != $id) abort(403);
        $latestData = Data::where('user_id', $user->id)->latest()->first();
        $firstRecord = Data::where('user_id', $user->id)->oldest()->first();
        $minDate = $firstRecord ? $firstRecord->created_at->format('Y-m-d\TH:i') : Carbon::now()->format('Y-m-d\TH:i');
        $maxDate = $latestData ? $latestData->created_at->format('Y-m-d\TH:i') : Carbon::now()->format('Y-m-d\TH:i');
        return view('user.user_home', [
            'user' => $user,
            'data' => $latestData,
            'minDate' => $minDate, 
            'maxDate' => $maxDate  
        ]);
    })->name('user.profile');


    
    Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/user/create', [AdminController::class, 'createUser'])->name('admin.user.create');
        Route::get('/user/{id}/view', [AdminController::class, 'viewUserDetails'])->name('admin.user.view');
    });

});