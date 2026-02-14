<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware{
    public function handle(Request $request, Closure $next){
        if (auth()->check() && auth()->user()->state_admin == 1) {
            return $next($request); 
        }
        abort(403, 'Сюда нельзя, ты не админ!');
    }
}

