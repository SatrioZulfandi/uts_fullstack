<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old constraint
        DB::statement('ALTER TABLE borrowing_schedules DROP CONSTRAINT IF EXISTS borrowing_schedules_status_check');
        
        // Add the new constraint with 'pending' included
        DB::statement("ALTER TABLE borrowing_schedules ADD CONSTRAINT borrowing_schedules_status_check CHECK (status::text = ANY (ARRAY['pending'::character varying, 'booked'::character varying, 'checked_in'::character varying, 'completed'::character varying, 'cancelled'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE borrowing_schedules DROP CONSTRAINT IF EXISTS borrowing_schedules_status_check');
        DB::statement("ALTER TABLE borrowing_schedules ADD CONSTRAINT borrowing_schedules_status_check CHECK (status::text = ANY (ARRAY['booked'::character varying, 'checked_in'::character varying, 'completed'::character varying, 'cancelled'::character varying]::text[]))");
    }
};
