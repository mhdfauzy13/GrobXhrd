<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleStatus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Pastikan user memiliki role yang aktif
        if ($user->roles->every(fn($role) => !$role->isActive())) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['msg' => 'Role is not active.']);
        }

        // Cek akses ke rute superadmin
        // Tambahkan pengecualian untuk rute 'dashboard.index'
        if ($request->is('superadmin/*') && !$user->hasRole('superadmin') && !$request->is('dashboard')) {
            return redirect()->route('dashboard.index')->withErrors(['msg' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        if ($request->is('manager/*') && !$user->hasRole('manager')) {
            return redirect()->route('dashboard.index')->withErrors(['msg' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        return $next($request);
    }
}

