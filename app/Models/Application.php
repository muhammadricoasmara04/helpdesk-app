<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Application extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'applications';
    protected $fillable = [
        'organization_id',
        'application_name',
        'description',
        'create_id',
        'updated_id'
    ];

    public function problems()
    {
        return $this->hasMany(ApplicationProblem::class, 'application_id');
    }
}
