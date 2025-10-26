<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TicketPriority extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'ticket_priority';
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
}
