<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = auth()->user();
            $redirectRoutes = [
                'Admin' => '/admin',
                'Manager' => '/manager',
                'Client' => '/client',
            ];

            if ($user->role === 'Admin' && $request->is('client*', 'manager*')) {
                return redirect($redirectRoutes['Admin']);
            }

            if ($user->role === 'Manager' && $request->is('admin*','client*')) {
                return redirect($redirectRoutes['Manager']);
            }

            if ($user->role === 'Client' && $request->is('admin*', 'manager*')) {
                return redirect($redirectRoutes['Client']);
            }
        }

        return $next($request);
    }
}
