<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk roles.
     */
    public function run(): void
    {
        $roles = [
            'admin' => '05f91f68-7d8b-480f-a00b-488c5957e32a',
            'user'  => 'e06e79ec-3d33-4129-9f55-1bac96361d40',
        ];

        foreach ($roles as $roleName => $uuid) {
            Role::updateOrCreate(
                ['id' => $uuid],
                ['name' => $roleName]
            );
        }
    }
}
