<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('food_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('food_order_id');
            $table->unsignedBigInteger('food_item_id');
            $table->string('item_name');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            // Foreign keys baad mein add karo
            $table->foreign('food_order_id')
                ->references('id')->on('food_orders')
                ->onDelete('cascade');

            $table->foreign('food_item_id')
                ->references('id')->on('food_items')
                ->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('food_order_items');
    }
};
