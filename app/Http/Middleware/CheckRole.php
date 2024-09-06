<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRoles = Auth::user()->roles->pluck('name')->toArray(); // Convert to array
        if (!in_array($role, $userRoles)) {
            abort(403, 'User does not have the right roles.');
        }

        return $next($request);
    }
}
