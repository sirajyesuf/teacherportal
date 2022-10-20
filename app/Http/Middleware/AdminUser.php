<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if(isset($user->id) && $user->role_type != 1 ){
            return redirect('home');
        }
        return $next($request);
    }
}
