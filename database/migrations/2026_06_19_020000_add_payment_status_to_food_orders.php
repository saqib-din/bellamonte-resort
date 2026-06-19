<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add the separate payment status column (auto-derived from amounts)
        Schema::table('food_orders', function (Blueprint $table) {
            $table->enum('payment_status', ['Unpaid', 'Partial', 'Paid', 'Refunded'])
                  ->default('Unpaid')
                  ->after('status');
        });

        // 2) Allow 'Completed' in the kitchen status enum (keep 'Paid' for now so existing rows stay valid)
        DB::statement("ALTER TABLE food_orders MODIFY status ENUM('Pending','Preparing','Served','Paid','Completed','Cancelled') NOT NULL DEFAULT 'Pending'");

        // 3) Backfill payment_status from the existing amounts
        DB::statement("UPDATE food_orders SET payment_status = CASE
            WHEN total_amount > 0 AND amount_paid >= total_amount THEN 'Paid'
            WHEN amount_paid > 0 THEN 'Partial'
            ELSE 'Unpaid' END");

        // 4) Convert the old mixed status 'Paid' into the kitchen status 'Completed'
        DB::statement("UPDATE food_orders SET status = 'Completed' WHERE status = 'Paid'");

        // 5) Drop 'Paid' from the kitchen status enum (it now lives in payment_status)
        DB::statement("ALTER TABLE food_orders MODIFY status ENUM('Pending','Preparing','Served','Completed','Cancelled') NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        // Restore the old mixed enum (map fully-paid Completed back to 'Paid')
        DB::statement("ALTER TABLE food_orders MODIFY status ENUM('Pending','Preparing','Served','Paid','Completed','Cancelled') NOT NULL DEFAULT 'Pending'");
        DB::statement("UPDATE food_orders SET status = 'Paid' WHERE status = 'Completed' AND payment_status = 'Paid'");
        DB::statement("ALTER TABLE food_orders MODIFY status ENUM('Pending','Preparing','Served','Paid','Cancelled') NOT NULL DEFAULT 'Pending'");

        Schema::table('food_orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
