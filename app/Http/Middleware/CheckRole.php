<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Convert $roles to an array if it's a string
        if (is_string($roles[0])) {
            $roles = [$roles[0]];
        }

        // Check if the user has any of the roles
        if (!Auth::user()->hasAnyRole($roles)) {
            abort(403, 'User does not have the right roles.');
        }

        return $next($request);
    }
}
