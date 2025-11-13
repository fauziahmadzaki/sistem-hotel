<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $routes = [
                "admin" => "dashboard.admin.index",
                "superadmin" => "dashboard.superadmin.index",
                "housekeeper" => "dashboard.housekeeping.index",
            ];

            return redirect()->route($routes[$user->role]);

        }

        return back()->with('error', 'Email atau password salah!');
}

    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->profile()->create([
                    'phone_number' => null,
                    'address'      => null,
                    'gender'       => null,
                ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal membuat akun: ' . $th->getMessage());
        }

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

  
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showLogin()
    {
        return view('auth.login');
    }


    public function showRegister()
    {
        return view('auth.register');
    }

    public function showResetPassword(){
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [ 
            'password' => 'required|min:8|max:32',
            'confirm_password' => 'required|same:password|min:8|max:32',    
        ], [
            'password.required' => 'Password harus diisi!',
            'password.min' => 'Password minimal 8 karakter!',
            'password.max' => 'Password maksimal 32 karakter!',
            'confirm_password.required' => 'Konfirmasi Password harus diisi!',
            'confirm_password.same' => 'Konfirmasi Password harus sama dengan Password!',
            'confirm_password.min' => 'Konfirmasi Password minimal 8 karakter!',
            'confirm_password.max' => 'Konfirmasi Password maksimal 32 karakter!',
        ]);
        $validated = $validator->validated();

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login.');
     
    }
}
