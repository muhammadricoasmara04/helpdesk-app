<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'ticket_status_id',
        'ticket_priority_id',
        'application_id',
        'application_problem_id',
        'ticked_code',
        'employee_number',
        'employee_name',
        'position_name',
        'organization_name',
        'subject',
        'description',
    ];
    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'ticket_priority_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function problem()
    {
        return $this->belongsTo(ApplicationProblem::class, 'application_problem_id');
    }
}
