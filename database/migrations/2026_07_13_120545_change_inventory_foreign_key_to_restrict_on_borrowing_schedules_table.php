<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mengubah foreign key inventory_id pada borrowing_schedules dari CASCADE menjadi RESTRICT.
 *
 * Alasan: Proses bisnis API menolak penghapusan inventory yang masih memiliki schedule (HTTP 409).
 * Database constraint harus konsisten dengan logic API untuk menjaga integritas data.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowing_schedules', function (Blueprint $table) {
            $table->dropForeign(['inventory_id']);
        });

        Schema::table('borrowing_schedules', function (Blueprint $table) {
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('borrowing_schedules', function (Blueprint $table) {
            $table->dropForeign(['inventory_id']);
        });

        Schema::table('borrowing_schedules', function (Blueprint $table) {
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories')
                ->cascadeOnDelete();
        });
    }
};
