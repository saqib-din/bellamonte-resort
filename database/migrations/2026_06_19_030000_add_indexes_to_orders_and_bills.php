<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
            $table->index('guest_name');
            $table->index('guest_phone');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->index('status');
            $table->index('issue_date');
            $table->index('total_amount');
            $table->index('guest_name');
            $table->index('guest_phone');
        });
    }

    public function down(): void
    {
        Schema::table('food_orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['guest_name']);
            $table->dropIndex(['guest_phone']);
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['issue_date']);
            $table->dropIndex(['total_amount']);
            $table->dropIndex(['guest_name']);
            $table->dropIndex(['guest_phone']);
        });
    }
};
