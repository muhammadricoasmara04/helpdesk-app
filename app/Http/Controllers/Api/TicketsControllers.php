<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class TicketsControllers extends Controller
{
    public function index()
    {
        try {
            $tickets = Ticket::with(['status', 'priority', 'application', 'problem'])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Tickets retrieved successfully.',
                'data' => $tickets
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while retrieving tickets.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ticket_status_id' => 'required|uuid|exists:ticket_status,id',
                'ticket_priority_id' => 'required|uuid|exists:ticket_priority,id',
                'application_id' => 'required|uuid|exists:applications,id',
                'application_problem_id' => 'required|uuid|exists:application_problems,id',
                'employee_number' => 'required|string|max:50',
                'employee_name' => 'required|string|max:255',
                'position_name' => 'required|string',
                'organization_name' => 'required|string',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
            ]);

            $ticket = Ticket::create([
                'id' => Str::uuid(),
                'ticket_status_id' => $validated['ticket_status_id'],
                'ticket_priority_id' => $validated['ticket_priority_id'],
                'application_id' => $validated['application_id'],
                'application_problem_id' => $validated['application_problem_id'],
                'ticked_code' => 'TCK-' . strtoupper(Str::random(8)),
                'employee_number' => $validated['employee_number'],
                'employee_name' => $validated['employee_name'],
                'position_name' => $validated['position_name'],
                'organization_name' => $validated['organization_name'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully.',
                'data' => $ticket
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while creating ticket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $ticket = Ticket::with(['status', 'priority', 'application', 'problem'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Ticket retrieved successfully.',
                'data' => $ticket
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while retrieving ticket.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            $validated = $request->validate([
                'ticket_status_id' => 'sometimes|uuid|exists:ticket_status,id',
                'ticket_priority_id' => 'sometimes|uuid|exists:ticket_priority,id',
                'application_id' => 'sometimes|uuid|exists:applications,id',
                'application_problem_id' => 'sometimes|uuid|exists:application_problems,id',
                'employee_number' => 'sometimes|string|max:50',
                'employee_name' => 'sometimes|string|max:255',
                'position_name' => 'sometimes|string',
                'organization_name' => 'sometimes|string',
                'subject' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
            ]);

            $ticket->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Ticket updated successfully.',
                'data' => $ticket
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 401);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while updating ticket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ticket deleted successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while deleting ticket.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function myTickets(Request $request)
    {
        try {
            $user = $request->user();
            $tickets = Ticket::with(['status', 'priority', 'application', 'problem'])
                ->where('employee_number', $user->id) 
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'User tickets retrieved successfully.',
                'data' => $tickets
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while retrieving user tickets.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
