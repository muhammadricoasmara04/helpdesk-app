<?php

namespace App\Http\Controllers\Api;

use App\Events\TicketCreated;
use App\Events\TicketStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class TicketsControllers extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $query = Ticket::with(['status', 'priority', 'application', 'problem']);

            // FILTER EMPLOYEE
            if ($request->has('employee_number')) {
                $query->where('employee_number', $request->employee_number);
            }

            // FILTER ROLE ADMIN
            if ($user->role === 'admin') {
                $query->where('assigned_to', $user->id);
            }

            // SEARCH
            if ($search = $request->search) {
                $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', "%$search%")
                        ->orWhere('ticket_code', 'like', "%$search%")
                        ->orWhere('employee_name', 'like', "%$search%");
                });
            }

            // FILTER STATUS
            if ($status = $request->status) {
                $query->whereHas('status', fn($q) => $q->where('slug', $status));
            }

            // FILTER PRIORITY
            if ($priority = $request->priority) {
                $query->whereHas('priority', function ($q) use ($priority) {
                    $q->whereRaw('LOWER(name) = ?', [strtolower($priority)]);
                });
            }

            // FILTER TANGGAL (SAFE)
            if ($request->filled('start_date') && strtotime($request->start_date)) {
                $query->whereDate('tickets.created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date') && strtotime($request->end_date)) {
                $query->whereDate('tickets.created_at', '<=', $request->end_date);
            }
            // CUSTOM SORT BY STATUS + CREATED AT
            $query->leftJoin('ticket_status', 'tickets.ticket_status_id', '=', 'ticket_status.id')
                ->orderByRaw("
                  CASE ticket_status.slug
                      WHEN 'open' THEN 1
                      WHEN 'in-progress' THEN 2
                      WHEN 'pending' THEN 3
                      WHEN 'close' THEN 4
                      ELSE 5
                  END
              ")
                ->orderBy('tickets.created_at', 'asc')
                ->select('tickets.*');

            $tickets = $query->paginate($request->get('per_page', 10));

            // UNREAD CHECK
            $tickets->getCollection()->transform(function ($ticket) use ($user) {
                $ticket->has_unread = \App\Models\TicketReply::where('ticket_id', $ticket->id)
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->exists();
                return $ticket;
            });

            return response()->json([
                'success' => true,
                'data' => $tickets
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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
                'ticket_code' => 'TCK-' . strtoupper(Str::random(8)),
                'employee_number' => $validated['employee_number'],
                'employee_name' => $validated['employee_name'],
                'position_name' => $validated['position_name'],
                'organization_name' => $validated['organization_name'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
            ]);
            event(new TicketCreated($ticket));

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

    public function assign($id, Request $request)
    {
        // Validasi staff_id wajib ada
        $request->validate([
            'staff_id' => 'required|exists:users,id'
        ]);

        // Ambil tiket
        $ticket = Ticket::findOrFail($id);

        // Jika sudah di-assign ke orang lain
        if ($ticket->assigned_to) {
            return response()->json(['message' => 'Tiket sudah di-handle oleh admin lain.'], 403);
        }

        // Ambil status "In Progress"
        $onProgressStatus = TicketStatus::where('name', 'In Progress')->first();

        // Assign ke staff yang dipilih
        $ticket->assigned_to = $request->staff_id;
        if ($onProgressStatus) {
            $ticket->ticket_status_id = $onProgressStatus->id;
        }

        $ticket->save();

        // Broadcast ke staff yang dituju
        broadcast(new TicketStatusUpdated($ticket))->toOthers();

        return response()->json([
            'message' => 'Tiket berhasil di-assign ke staff.',
            'data' => $ticket
        ]);
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
            $perPage = $request->input('per_page', 10);

            $query = Ticket::with(['status', 'priority', 'application', 'problem'])
                ->where('employee_number', $user->id)
                ->addSelect([
                    'has_unread' => \App\Models\TicketReply::selectRaw('COUNT(*)')
                        ->whereColumn('ticket_id', 'tickets.id')
                        ->where('user_id', '!=', $user->id)
                        ->where('is_read', false)
                ]);

            // 🔍 Filter Search
            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', "%$search%")
                        ->orWhere('ticket_code', 'like', "%$search%")
                        ->orWhere('employee_name', 'like', "%$search%");
                });
            }

            // 📌 Filter Status
            if ($request->status) {
                $query->whereHas('status', function ($q) use ($request) {
                    $q->where('slug', $request->status);
                });
            }

            // 📌 Filter Priority
            if ($request->priority) {
                $query->whereHas('priority', function ($q) use ($request) {
                    $q->where('slug', $request->priority);
                });
            }

            if ($request->filled('start_date')) {
                $query->whereDate('tickets.created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('tickets.created_at', '<=', $request->end_date);
            }


            $query->leftJoin('ticket_status', 'tickets.ticket_status_id', '=', 'ticket_status.id')
                ->orderByRaw("
                  CASE ticket_status.slug
                      WHEN 'open' THEN 1
                      WHEN 'in-progress' THEN 2
                      WHEN 'pending' THEN 3
                      WHEN 'close' THEN 4
                      ELSE 5
                  END
              ")
                ->orderBy('tickets.created_at', 'asc')
                ->select('tickets.*');
            // Pagination tetap
            $tickets = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'User tickets retrieved successfully.',
                'data' => $tickets->items(),
                'pagination' => [
                    'current_page' => $tickets->currentPage(),
                    'last_page' => $tickets->lastPage(),
                    'per_page' => $tickets->perPage(),
                    'total' => $tickets->total(),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while retrieving user tickets.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
