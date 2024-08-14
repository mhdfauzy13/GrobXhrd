<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleStatus
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $roles = $user->roles;

        foreach ($roles as $role) {
            if ($role->status === 'disable') {
                Auth::logout();
                return redirect('/login')->withErrors(['account_disabled' => 'Role Anda dinonaktifkan.']);
            }
        }

        return $next($request);
    }
}
