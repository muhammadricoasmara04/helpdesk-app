<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TicketStatus extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'ticket_status';
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
}
