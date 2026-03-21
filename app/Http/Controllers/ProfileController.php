<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi untuk update profil (nama & no_hp)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        // Update nama dan no_hp
        $user->update([
            'name' => $validated['name'],
            'no_hp' => $validated['no_hp'],
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validasi untuk update password
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // Cek password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->withInput();
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password berhasil diubah.');
    }
}
