<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {

            $table->id();

            $table->string('room_number')->unique();

            $table->string('type');

            $table->integer('floor');

            $table->decimal('price_per_night', 10, 2);

            $table->integer('capacity');

            $table->string('size')->nullable();

            $table->string('bed_type')->nullable();

            $table->text('services')->nullable();

            $table->longText('description')->nullable();

            $table->time('check_in_time')->nullable();

            $table->time('check_out_time')->nullable();

            $table->string('image')->nullable();

            $table->enum('status', [
                'Available',
                'Occupied',
                'Maintenance'
            ])->default('Available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
