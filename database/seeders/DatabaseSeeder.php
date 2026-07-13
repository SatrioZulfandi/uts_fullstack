<?php

namespace Database\Seeders;

use App\Models\BorrowingSchedule;
use App\Models\Inventory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk mengisi data awal Smart-Hub Management System.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ========================================
        // 1. Buat User Admin
        // ========================================
        $admin = User::create([
            'name' => 'Admin Smart-Hub',
            'email' => 'admin@smarthub.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // ========================================
        // 2. Buat User Member
        // ========================================
        $member1 = User::create([
            'name' => 'Satrio',
            'email' => 'satrio@member.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        $member2 = User::create([
            'name' => 'Zulfandi',
            'email' => 'zulfandi@member.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        // ========================================
        // 3. Buat Data Inventaris
        // ========================================
        $workspace1 = Inventory::create([
            'name' => 'Ruang Kreatif A',
            'type' => 'workspace',
            'status' => 'available',
            'description' => 'Ruang kerja bersama kapasitas 10 orang dengan proyektor.',
        ]);

        $workspace2 = Inventory::create([
            'name' => 'Studio Podcast B',
            'type' => 'workspace',
            'status' => 'available',
            'description' => 'Studio podcast kedap suara dengan peralatan recording.',
        ]);

        $equipment1 = Inventory::create([
            'name' => 'Kamera Sony A7III',
            'type' => 'equipment',
            'status' => 'available',
            'description' => 'Kamera mirrorless full-frame untuk foto dan video.',
        ]);

        $equipment2 = Inventory::create([
            'name' => 'Laptop MacBook Pro 16"',
            'type' => 'equipment',
            'status' => 'maintenance',
            'description' => 'Laptop editing video dengan chip M3 Pro.',
        ]);

        // ========================================
        // 4. Buat Jadwal Peminjaman Contoh
        // ========================================
        BorrowingSchedule::create([
            'user_id' => $member1->id,
            'inventory_id' => $workspace1->id,
            'start_time' => Carbon::now()->addDay(),
            'end_time' => Carbon::now()->addDay()->addHours(3),
            'status' => 'booked',
        ]);

        BorrowingSchedule::create([
            'user_id' => $member2->id,
            'inventory_id' => $equipment1->id,
            'start_time' => Carbon::now()->addDays(2),
            'end_time' => Carbon::now()->addDays(2)->addHours(5),
            'status' => 'booked',
        ]);
    }
}
