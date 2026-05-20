<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        if (Auth::guard($guard)->check()) { 
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('prestador')) {
                return redirect()->route('provider.orders.index');
            }

            return redirect('/admin'); //redirect if admin is logged
        }
        
        return $next($request);
    }
}
