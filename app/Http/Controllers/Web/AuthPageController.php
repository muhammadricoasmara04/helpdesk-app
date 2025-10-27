<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthPageController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        
        Auth::login($user);

        // Buat token untuk kebutuhan API
        $token = $user->createToken('auth_token')->plainTextToken;
        session(['api_token' => $token]);
        session(['user' => [
            'id' => $user->id,
            'name' => $user->name,
            'role_id' => $user->role_id,
        ]]);

        // Redirect sesuai role
        return redirect($user->role->name === 'admin' ? '/dashboard' : '/dashboard/user');
    }


    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
           
            $user->tokens()->delete(); 
            Auth::logout(); 
        }

        // Hapus session yang dibuat sebelumnya
        $request->session()->forget('user');
        $request->session()->forget('api_token');

        return redirect('/')->with('success', 'Berhasil logout');
    }
}
