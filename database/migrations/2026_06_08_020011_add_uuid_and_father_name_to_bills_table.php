<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
            $table->string('father_name')->nullable()->after('guest_name');
        });

        DB::table('bills')->whereNull('uuid')->orderBy('id')->each(function ($b) {
            DB::table('bills')->where('id', $b->id)
                ->update(['uuid' => (string) Str::uuid()]);
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn(['uuid', 'father_name']);
        });
    }
};
