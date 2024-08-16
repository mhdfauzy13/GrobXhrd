<?php

// app/Http/Middleware/CheckRoleStatus.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleStatus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user tidak terautentikasi, langsung lanjutkan
        if (!$user) {
            return $next($request);
        }

        // Memeriksa setiap role user
        foreach ($user->roles as $role) {
            if (!$role->isActive()) {
                Auth::logout(); // Logout user
                return redirect()->route('login')->withErrors(['Your role has been deactivated.']);
            }
        }

        return $next($request);
    }
}


