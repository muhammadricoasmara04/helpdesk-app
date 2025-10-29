<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $table = 'ticket_attachments';


    protected $fillable = [
        'ticket_id',
        'file_path',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }


    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
