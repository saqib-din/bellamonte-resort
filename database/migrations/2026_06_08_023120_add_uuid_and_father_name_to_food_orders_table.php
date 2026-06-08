<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('food_orders', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
            $table->string('father_name')->nullable()->after('guest_name');
        });

        DB::table('food_orders')->whereNull('uuid')->orderBy('id')->each(function ($row) {
            DB::table('food_orders')
                ->where('id', $row->id)
                ->update(['uuid' => (string) Str::uuid()]);
        });

        Schema::table('food_orders', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('food_orders', function (Blueprint $table) {
            $table->dropUnique('food_orders_uuid_unique');
            $table->dropColumn(['uuid', 'father_name']);
        });
    }
};
