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
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
            $table->string('slug')->nullable()->after('uuid');
        });

        DB::table('events')->orderBy('id')->each(function ($event) {
            $base = Str::slug($event->title) ?: 'event';
            $slug = $base;
            $i = 1;
            while (DB::table('events')->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            DB::table('events')->where('id', $event->id)->update([
                'uuid' => (string) Str::uuid(),
                'slug' => $slug,
            ]);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropUnique(['slug']);
            $table->dropColumn(['uuid', 'slug']);
        });
    }
};
