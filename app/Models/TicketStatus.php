<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class TicketStatus extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'ticket_status';
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

     protected static function booted()
    {
        static::saving(function ($status) {
            $status->slug = Str::slug($status->name);
        });

        /*
        static::creating(function ($status) {
            $status->slug = Str::slug($status->name);
        });
        */
    }
}
