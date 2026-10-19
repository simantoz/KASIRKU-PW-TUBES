<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('owner.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['owner', 'gudang', 'kasir'])],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()->route('owner.users.index')
            ->with('success', 'Akun pegawai berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(['owner', 'gudang', 'kasir'])],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Prevent self deactivate
        if ($user->id === Auth::id() && !$validated['is_active']) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update($validated);

        return redirect()->route('owner.users.index')
            ->with('success', 'Data akun pegawai berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('owner.users.index')
            ->with('success', 'Akun pegawai berhasil dihapus.');
    }
}
