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
                'Sekretariat Jenderal' => 'f2a5976f-a0a6-4b47-b3f1-ed3dee8e586e',
                'Inspektorat Jenderal'  => '38cf7d3c-0618-4f9b-b0bb-74e544c736d0',
                'Direktorat Jenderal Imigrasi'  => '12b0ae20-21fc-4712-80f9-51b13d79c5bb',
                'Direktorat Jenderal Pemasyarakatan'  => '981a3067-6dc9-4ca9-9563-b34634794101',
                'Badan Pengembangan Sumber Daya Manusia'  => 'f5063926-daef-485d-836f-a7b4ad425820',
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
