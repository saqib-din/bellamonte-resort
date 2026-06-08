<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        DB::table('rooms')->whereNull('uuid')->orderBy('id')->each(function ($room) {
            DB::table('rooms')
                ->where('id', $room->id)
                ->update(['uuid' => (string) Str::uuid()]);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropUnique('rooms_uuid_unique');
            $table->dropColumn('uuid');
        });
    }
};
