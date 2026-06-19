<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('day_rate', 10, 2)->nullable()->after('price_per_night');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('day_rate');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['day_rate', 'hourly_rate']);
        });
    }
};
