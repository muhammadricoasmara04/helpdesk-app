<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketPriority;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketsPriorityController extends Controller
{
    public function index()
    {
        try {
            $priorities = TicketPriority::latest()->get();
            return response()->json([
                'success' => true,
                'data' => $priorities
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $priority = TicketPriority::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket priority created successfully',
                'data' => $priority
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        $priority = TicketPriority::find($id);

        if (!$priority) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket priority not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $priority
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $priority = TicketPriority::find($id);

        if (!$priority) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket priority not found'
            ], 404);
        }

        try {
            $priority->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket priority updated successfully',
                'data' => $priority
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        $priority = TicketPriority::find($id);

        if (!$priority) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket priority not found'
            ], 404);
        }

        try {
            $priority->delete();
            return response()->json([
                'success' => true,
                'message' => 'Ticket priority deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
