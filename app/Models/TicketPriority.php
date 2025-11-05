<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class TicketPriority extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'ticket_priority';
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
    public function applicationProblems()
    {
        return $this->hasMany(ApplicationProblem::class, 'ticket_priority_id');
    }

    protected static function booted()
    {
        // Kalau mau slug update setiap kali name berubah:
        static::saving(function ($priority) {
            $priority->slug = Str::slug($priority->name);
        });

        /*
        static::creating(function ($priority) {
            $priority->slug = Str::slug($priority->name);
        });
        */
    }
}
