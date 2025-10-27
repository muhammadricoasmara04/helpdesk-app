<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $apiController = new AuthController();
        $apiResponse = $apiController->login($request);
        $data = $apiResponse->getData();
        $userData = $data->user; // stdClass

        $token = $data->token ?? null;

        session([
            'user' => (array) $userData,
            'api_token' => $token,
        ]);

        // Redirect sesuai role
        return redirect($userData->role_id === 1 ? '/dashboard' : '/dashboard/user');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        // Auth::logout(); // jika menggunakan Laravel auth
        return redirect('/');
    }
}
