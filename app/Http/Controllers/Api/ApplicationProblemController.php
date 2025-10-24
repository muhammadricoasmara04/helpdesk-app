<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApplicationProblem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationProblemController extends Controller
{
    public function index()
    {
        try {
            $problems = ApplicationProblem::latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'Application problems retrieved successfully.',
                'data' => $problems
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while retrieving problems.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $problem = ApplicationProblem::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Application problem retrieved successfully.',
                'data' => $problem
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application problem not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'application_id' => 'required|uuid',
            'problem_name' => 'required|string|max:255',
            'description' => 'required|string',
            'created_id' => 'nullable|uuid',
            'updated_id' => 'nullable|uuid',
        ]);

        try {
            $problem = ApplicationProblem::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Application problem created successfully.',
                'data' => $problem
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while creating problem.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'application_id' => 'sometimes|uuid',
            'problem_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'created_id' => 'sometimes|uuid',
            'updated_id' => 'sometimes|uuid',
        ]);

        try {
            $problem = ApplicationProblem::findOrFail($id);
            $problem->update($request->all());
            $problem->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Application problem updated successfully.',
                'data' => $problem,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application problem not found or server error.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $problem = ApplicationProblem::findOrFail($id);
            $problem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Application problem deleted successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application problem not found or server error.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
