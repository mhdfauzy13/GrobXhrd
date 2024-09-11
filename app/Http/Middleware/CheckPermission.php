<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        // Periksa apakah user yang sedang login memiliki permission yang dibutuhkan
        if (!auth()->user()->can($permission)) {
            // Jika tidak, tampilkan halaman error 403 (Unauthorized)
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
