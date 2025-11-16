<?php

namespace Database\Seeders;

use App\Models\Organization;
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
            'staff'  => '86bfcec2-50b5-4679-a947-1c1e4b33a0c2',
        ];

        foreach ($roles as $roleName => $uuid) {
            Role::updateOrCreate(
                ['id' => $uuid],
                ['name' => $roleName]
            );

            $organizations = [
                'Acme Corp' => 'f2a5976f-a0a6-4b47-b3f1-ed3dee8e586e',
                'Beta Ltd'  => '38cf7d3c-0618-4f9b-b0bb-74e544c736d0',
            ];

            foreach ($organizations as $orgName => $uuid) {
                Organization::updateOrCreate(
                    ['id' => $uuid],
                    ['organization' => $orgName, 'status' => 'active']
                );
            }
        }
    }
}
