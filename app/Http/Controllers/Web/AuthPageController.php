<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SSOService;
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
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        $nip = $request->nip;
        $password = $request->password;

        // ================
        // LOGIN LOCAL (ADMIN / STAFF)
        // ================
        $localUser = User::where('nip', $nip)->first();

        if ($localUser) {
            $api = new AuthController();
            $response = $api->login($request);
            $data = $response->getData(true);

            if (!isset($data['token'])) {
                return back()->withErrors(['nip' => $data['message'] ?? 'Login gagal']);
            }

            Auth::login($localUser);
            Session::put('api_token', $data['token']);
            Session::put('user', $data['user']);

            $role = $data['user']['role'];
            return redirect($role === 'admin' ? '/dashboard' : '/dashboard/user');
        }

        // ================
        // LOGIN VIA SSO
        // ================
        $sso = new SSOService();

        try {
            $tokenResponse = $sso->getToken($nip, $password);

            if (!$tokenResponse->successful()) {
                return back()->withErrors(['nip' => 'NIP atau password SSO salah']);
            }

            $tokenData = $tokenResponse->json();

            // Ambil user profile
            $userInfo = $sso->getUserInfo($tokenData['access_token']);

            if (!$userInfo->successful()) {
                return back()->withErrors(['sso' => 'Gagal mengambil data user SSO']);
            }

            $ssoUser = $userInfo->json();

            // Simpan session
            Session::put('sso_user', $ssoUser);
            Session::put('sso_token', $tokenData['access_token']);

            return redirect('/dashboard/user');
        } catch (\Exception $e) {
            return back()->withErrors(['sso' => $e->getMessage()]);
        }
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
        Session::forget('sso_user');
        $request->session()->forget('api_token');


        return redirect('/')->with('success', 'Berhasil logout');
    }
}
