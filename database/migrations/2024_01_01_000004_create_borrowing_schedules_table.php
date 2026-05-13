<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel borrowing_schedules: menyimpan jadwal peminjaman & check-in.
     */
    public function up(): void
    {
        Schema::create('borrowing_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');                                         // Relasi ke tabel users
            $table->foreignId('inventory_id')
                  ->constrained('inventories')
                  ->onDelete('cascade');                                         // Relasi ke tabel inventories
            $table->datetime('start_time');                                      // Waktu mulai peminjaman
            $table->datetime('end_time');                                        // Waktu selesai peminjaman
            $table->enum('status', ['booked', 'checked_in', 'completed', 'cancelled'])
                  ->default('booked');                                           // Status peminjaman
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_schedules');
    }
};
