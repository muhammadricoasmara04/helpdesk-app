<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

       
        if (!in_array($user->role->name, (array) $roles)) {
     
            if ($user->role->name === 'admin') {
                return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman user.');
            }
            return redirect('/dashboard/user')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}
