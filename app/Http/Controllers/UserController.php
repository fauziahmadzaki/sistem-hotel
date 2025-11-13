<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
     
        $users = User::latest()->paginate(10);
        
        return view('dashboard.users.index', compact('users'));
    }


    public function create()
    {
        $roles = ['admin' => 'Admin (Kasir)', 'housekeeper' => 'Housekeeper', 'guest' => 'Tamu'];
        return view('dashboard.users.create', compact('roles'));
    }

    
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        
        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('dashboard.users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

 
    public function edit(User $user)
    {
        
        if ($user->role === 'superadmin') {
            return redirect()->route('dashboard.users.index')->with('error', 'Superadmin tidak dapat diedit.');
        }

        $roles = ['admin' => 'Admin (Kasir)', 'housekeeper' => 'Housekeeper', 'guest' => 'Tamu'];
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

       
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); 
        }

        $user->update($validated);

        return redirect()->route('dashboard.users.index')->with('success', 'Data user berhasil diperbarui.');
    }


    public function destroy(User $user)
    {
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Akun Superadmin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('dashboard.users.index')->with('success', 'User berhasil dihapus.');
    }
}