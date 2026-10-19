<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Akun Anda dinonaktifkan. Silakan hubungi Owner.']);
        }

        if (!in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak untuk hak akses role Anda.'
                ], 403);
            }

            // Redirect to their default dashboard based on role with warning message
            $targetRoute = match ($user->role) {
                'owner' => 'owner.dashboard',
                'gudang' => 'gudang.dashboard',
                'kasir' => 'kasir.pos',
                default => 'login',
            };

            return redirect()->route($targetRoute)->with('warning', 'Akses ditolak: Halaman tersebut dibatasi hanya untuk role tertentu.');
        }

        return $next($request);
    }
}
