<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRedirect
{
    /**
     * Handle an incoming request.
     * Redirect admin/super_admin users to admin dashboard
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has admin role
        if (auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
