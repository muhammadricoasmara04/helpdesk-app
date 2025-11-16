<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * LOGIN USER
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip'    => 'required',
            'password' => 'required|string',
        ]);

        $user = User::where('nip', $credentials['nip'])->first();


        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'NIP atau password salah.',
            ], 401);
        }


        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'user'    => [
                'id'      => $user->id,
                'nip'   => $user->nip,
                'name'    => $user->name,
                'email'   => $user->email,
                'role_id' => $user->role_id,
                'role'    => $user->role->name ?? 'user',
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * REGISTER USER
     */
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'nip'          => 'required|string|max:255',
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email',
                'password'      => 'required|string|min:6|confirmed',
                'role' => 'nullable|string|in:admin,staff,user',
                'position_name'          => 'required|string|max:255',
                'organization'  => 'nullable|string|exists:organizations,organization',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
           if (isset($e->errors()['nip'])) {
                return response()->json([
                    'message' => 'NIP sudah terdaftar.'
                ], 409); // 409 Conflict
            }

            if (isset($e->errors()['email'])) {
                return response()->json([
                    'message' => 'Email sudah terdaftar.'
                ], 409); // 409 Conflict
            }

            // Tangani error validasi lain
            return response()->json([
                'message' => 'Data tidak valid.',
                'errors'  => $e->errors(),
            ], 422);
        }

        // Role dan organization
        $role = Role::where('name', $data['role'] ?? 'user')->first();
        $organization = null;
        if ($request->organization) {
            $organization = Organization::where('organization', $request->organization)->first();
        }

        // Buat user baru
        $user = User::create([
            'nip'   => $data['nip'],
            'name'             => $data['name'],
            'email'            => $data['email'],
            'password'         => Hash::make($data['password']),
            'position_name'    => $data['position_name'],
            'role_id'          => $role?->id,
            'organization_id'  => $organization?->id,
        ]);

        $token = $user->createToken('register_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'user'    => [
                'id'            => $user->id,
                'nip'          => $user->nip,
                'name'          => $user->name,
                'email'         => $user->email,
                'role_id'       => $user->role_id,
                'role'          => $role->name ?? 'user',
                'position_name' => $user->position_name,
                'organization'  => $organization?->organization,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }


    /**
     * LOGOUT USER
     */
    public function logout(Request $request)
    {
        // Hapus hanya token yang sedang aktif
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.'
        ]);
    }

    /**
     * PROFILE / USER INFO
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
