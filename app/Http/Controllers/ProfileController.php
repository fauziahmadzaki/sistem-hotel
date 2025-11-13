<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest; 
use App\Http\Requests\UpdatePasswordRequest; 

class ProfileController extends Controller
{

    public function index()
    {
        return view('dashboard.profile', [
            'user' => auth()->user()
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
       
        $validated = $request->validated();
        
        auth()->user()->update($validated);

        return redirect()->route('dashboard.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

   
    public function updatePassword(UpdatePasswordRequest $request)
    {
   
        $validated = $request->validated();

        auth()->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('dashboard.profile.index')->with('success', 'Password berhasil diperbarui.');
    }
}