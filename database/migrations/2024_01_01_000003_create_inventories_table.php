<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel inventories: menyimpan data peralatan & ruang kerja.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                              // Nama inventaris
            $table->enum('type', ['workspace', 'equipment']);                    // Tipe: ruang kerja atau peralatan
            $table->enum('status', ['available', 'maintenance', 'borrowed'])
                  ->default('available');                                        // Status ketersediaan
            $table->text('description')->nullable();                             // Deskripsi inventaris
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
