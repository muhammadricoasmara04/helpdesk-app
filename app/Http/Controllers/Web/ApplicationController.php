<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.application.index');
    }
    public function store()
    {
        return view('dashboard.admin.application.store');
    }

    public function show($id)
    {
        return view('dashboard.admin.application.show', compact('id'));
    }
    public function edit($id)
    {
        $application = Application::findOrFail($id);
        return view('dashboard.admin.application.edit', compact('application'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'application_name' => 'required|string|max:255',
            'description' => 'required|string',
            'application_code' => 'required|string',
        ]);

        $application = Application::findOrFail($id);

        // Ambil user login untuk updated_id
        $user = Auth::user();
        if ($user) {
            $validated['updated_id'] = $user->id;
        }

        $application->update($validated);

        return redirect('/dashboard/application')->with('success', 'Aplikasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Ambil token user dari session atau storage
        $token = session('token'); // pastikan token ini di-set saat login

        // URL ke API kamu
        $apiUrl = env('API_BASE_URL') . "/applications/{$id}";

        // Panggil API DELETE
        $response = Http::withToken($token)->delete($apiUrl);

        if ($response->successful()) {
            return redirect('/dashboard/application')
                ->with('success', 'Aplikasi berhasil dihapus melalui API.');
        } else {
            return redirect('/dashboard/application')
                ->with('error', 'Gagal menghapus aplikasi: ' . $response->json('message'));
        }
    }
}
