<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ApplicationProblem extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'application_problems';
    protected $fillable = [
        'application_id',
        'ticket_priority_id',
        'problem_name',
        'description',
        'created_id',
        'updated_id'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
    public function ticketPriority()
    {
        return $this->belongsTo(TicketPriority::class, 'ticket_priority_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_id');
    }
}
