<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\TicketStatus;
use Exception;


class TicketsStatusController extends Controller
{
    public function index()
    {
        try {
            $status = TicketStatus::latest()->get();
            return response()->json([
                'success' => true,
                'data' => $status
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
            $status = TicketStatus::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket status created successfully',
                'data' => $status
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
        $status = TicketStatus::find($id);

        if (!$status) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket status not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $status
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $status = TicketStatus::find($id);

        if (!$status) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket status not found'
            ], 404);
        }

        try {
            $status->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully',
                'data' => $status
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
        $status = TicketStatus::find($id);

        if (!$status) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket status not found'
            ], 404);
        }

        try {
            $status->delete();
            return response()->json([
                'success' => true,
                'message' => 'Ticket status deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
