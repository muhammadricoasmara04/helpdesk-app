<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthPageController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_base_url');
    }
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $apiController = new AuthController();

        // Panggil method login dari API controller
        $response = $apiController->login($request);

        $data = $response->getData(true); // ambil array dari JSON

        if (!isset($data['token'])) {
            return back()->withErrors(['email' => $data['message'] ?? 'Login gagal']);
        }
        $user = User::where('email', $data['user']['email'])->first();
        Auth::login($user);
        Session::put('api_token', $data['token']);
        Session::put('user', $data['user']);

        $role = $data['user']['role'] ?? 'user';
        $redirectUrl = $role === 'admin' ? '/dashboard' : '/dashboard/user';

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
