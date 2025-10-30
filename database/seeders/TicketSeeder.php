<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ticket_status')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Open',
                'slug' => 'open',
                'description' => 'Ticket is open and awaiting action',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'In Progress',
                'slug' => 'in-progress',
                'description' => 'Ticket is being worked on',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Closed',
                'slug' => 'closed',
                'description' => 'Ticket is closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Isi ticket_priority
        DB::table('ticket_priority')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Low',
                'slug' => 'low',
                'description' => 'Low priority',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Medium',
                'slug' => 'medium',
                'description' => 'Medium priority',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'High',
                'slug' => 'high',
                'description' => 'High priority',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
