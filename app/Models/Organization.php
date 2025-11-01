<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Organization extends Model
{
    protected $fillable = ['id', 'organization', 'status'];
    protected $keyType = 'string';
    public $incrementing = false;
    public function organizations()
    {
        return $this->hasMany(User::class);
    }
}
