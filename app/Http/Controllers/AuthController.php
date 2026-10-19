<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan oleh administrator. Silakan hubungi Owner.',
                ]);
            }

            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user->role)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }

    private function redirectBasedOnRole(string $role)
    {
        return match ($role) {
            'owner' => redirect()->route('owner.dashboard'),
            'gudang' => redirect()->route('gudang.dashboard'),
            'kasir' => redirect()->route('kasir.pos'),
            default => redirect()->route('login'),
        };
    }
}
