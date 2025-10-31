<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthPageController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_base_ur');
    }
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

        // Buat token untuk kebutuhan API
        $token = $user->createToken('auth_token')->plainTextToken;
        session([
            'api_token' => $token,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role_id' => $user->role_id,
                'role' => $user->role->name ?? 'user',
            ],
        ]);

        $roleName = $user->role->name ?? 'user';

        $redirectUrl = $roleName === 'admin' ? '/dashboard' : '/dashboard/user';

        return redirect($redirectUrl);
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
