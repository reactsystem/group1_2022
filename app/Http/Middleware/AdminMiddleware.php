<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $user = Auth::user();
        if ($user->group_id == 1) {
            return $next($request);
        } else {
            return redirect('/home');
        }
    }

}
