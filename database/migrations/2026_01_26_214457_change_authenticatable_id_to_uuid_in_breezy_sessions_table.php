<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE filament_lab.public.breezy_sessions ALTER COLUMN authenticatable_id TYPE UUID USING authenticatable_id::text::uuid');
        } else {
            Schema::table('breezy_sessions', function (Blueprint $table) {
                $table->uuid('authenticatable_id')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE filament_lab.public.breezy_sessions ALTER COLUMN authenticatable_id TYPE BIGINT USING authenticatable_id::text::bigint');
        } else {
            Schema::table('breezy_sessions', function (Blueprint $table) {
                $table->unsignedBigInteger('authenticatable_id')->change();
            });
        }
    }
};
