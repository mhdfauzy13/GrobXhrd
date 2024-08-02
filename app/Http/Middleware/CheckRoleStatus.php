<?php

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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // public function handle(Request $request, Closure $next)
    // {
    //     $role = $request->user()->roles()->first();

    //     if ($role && $role->status == 'disable') {
    //         return redirect()->route('home')->with('error', 'Akses ditolak. Role Anda dinonaktifkan.');
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role && $user->role->status == 'disable') {
            return redirect()->route('home')->with('error', 'Akses ditolak. Role Anda dinonaktifkan.');
        }

        return $next($request);
    }
}
