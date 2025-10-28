<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function index()
    {
        try {
            $applications = Application::all();

            return response()->json([
                'success' => true,
                'message' => 'Applications retrieved successfully.',
                'data' => $applications,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while retrieving applications.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|uuid',
            'description' => 'required|string',
            'application_name' => 'required|string|max:255',
            'application_code' => 'required|string|max:25',
            'create_id' => 'required|uuid',
            'updated_id' => 'required|uuid',
        ]);

        try {
            $application = Application::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Application created successfully.',
                'data' => $application,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while creating application.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $application = Application::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Application retrieved successfully.',
                'data' => $application,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found or server error.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'organization_id' => 'sometimes|uuid',
            'description' => 'sometimes|string',
            'application_name' => 'sometimes|string|max:255',
            'application_code' => 'required|string|max:25',
            'create_id' => 'sometimes|uuid',
            'updated_id' => 'sometimes|uuid',
        ]);

        try {
            $application = Application::findOrFail($id);
            $data = $request->only([
                'organization_id',
                'application_name',
                'application_code',
                'description',
                'create_id',
                'updated_id',
            ]);

            Log::info('Data to update:', $data);
            $updated = $application->update($data);
            Log::info('Update result:', ['updated' => $updated]);
            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully.',
                'data' => $application,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found or server error.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            $application = Application::findOrFail($id);
            $application->delete();

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found or server error.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
