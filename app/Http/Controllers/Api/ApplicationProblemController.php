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
          $problems = ApplicationProblem::with('creator:id,name','updater:id,name')->latest()->get();

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

            $problem = ApplicationProblem::with(['creator:id,name', 'updater:id,name'])->findOrFail($id);

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
            'ticket_priority_id' => 'required|uuid|exists:ticket_priority,id',
            'problem_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan atau tidak login.',
                ], 401);
            }
            $problem = ApplicationProblem::create([
                'application_id' => $request->application_id,
                'ticket_priority_id' => $request->ticket_priority_id,
                'problem_name' => $request->problem_name,
                'description' => $request->description,
                'created_id' => $user->id,
                'updated_id' => $user->id,
            ]);

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
            'ticket_priority_id' => 'sometimes|uuid|exists:ticket_priority,id',
            'problem_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        try {
            $problem = ApplicationProblem::findOrFail($id);

            $data = $request->only(['application_id', 'ticket_priority_id', 'problem_name', 'description']);

            $user = $request->user();
            if ($user) {
                $data['updated_id'] = $user->id;
            }

            $problem->update($data);
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
