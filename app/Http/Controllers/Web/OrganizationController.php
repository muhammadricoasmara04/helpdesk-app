<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
     public function index()
    {
        $organizations = Organization::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.admin.organization.index', compact('organizations'));
    }

    public function create()
    {
        return view('dashboard.admin.organization.store');
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        Organization::create($request->only(['organization', 'status']));

        return redirect()->route('organization.index')->with('success', 'Organisasi berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $organization = Organization::findOrFail($id);
        return view('dashboard.admin.organization.show', compact('organization'));
    }

    public function edit(string $id)
    {
        $organization = Organization::findOrFail($id);
        return view('dashboard.admin.organization.edit', compact('organization'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'organization' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        $organization = Organization::findOrFail($id);
        $organization->update($request->only(['organization', 'status']));

        return redirect()->route('organization.index')->with('success', 'Organisasi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return redirect()->route('organization.index')->with('success', 'Organisasi berhasil dihapus.');
    }
}
