<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
        $users = User::with('role')->orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('isAdmin');
        
        $roles = Role::all();
        $pondokCabangList = Santri::getPondokCabangList();
        return view('users.create', compact('roles', 'pondokCabangList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'no_hp' => 'nullable|string|max:20',
            'pondok_cabang' => 'nullable|array',
            'pondok_cabang.*' => 'string|max:10',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Remove pondok_cabang from validated as it's not a column in users table
        $pondokCabang = $validated['pondok_cabang'] ?? [];
        unset($validated['pondok_cabang']);

        $user = User::create($validated);

        // Sync pondok cabang if role is ustad
        $role = Role::find($validated['role_id']);
        if ($role && $role->slug === 'ustad') {
            $user->syncPondokCabang($pondokCabang);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('role');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('isAdmin');
        
        $roles = Role::all();
        $pondokCabangList = Santri::getPondokCabangList();
        $userPondokCabang = $user->pondokCabang();
        return view('users.edit', compact('user', 'roles', 'pondokCabangList', 'userPondokCabang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'no_hp' => 'nullable|string|max:20',
            'pondok_cabang' => 'nullable|array',
            'pondok_cabang.*' => 'string|max:10',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Remove pondok_cabang from validated as it's not a column in users table
        $pondokCabang = $validated['pondok_cabang'] ?? [];
        unset($validated['pondok_cabang']);

        $user->update($validated);

        // Sync pondok cabang if role is ustad
        $role = Role::find($validated['role_id']);
        if ($role && $role->slug === 'ustad') {
            $user->syncPondokCabang($pondokCabang);
        } else {
            // Clear pondok cabang if not ustad
            $user->syncPondokCabang([]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('isAdmin');
        
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
