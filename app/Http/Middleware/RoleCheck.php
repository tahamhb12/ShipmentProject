<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = auth()->user();
            if ($user->role == 'Admin' && $request->is('client')) {
                return redirect('/admin');
            }
            if ($user->role == 'Admin' && $request->is('client/*')) {
                return redirect('/admin');
            }
            if ($user->role == 'Client' && $request->is('admin/*')) {
                return redirect('/client');
            }
            if ($user->role == 'Client' && $request->is('admin')) {
                return redirect('/client');
            }
        }
        return $next($request);
    }
}
