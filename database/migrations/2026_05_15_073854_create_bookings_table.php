<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();       // BK-2024-0001
            $table->foreignId('room_id')
                ->constrained('rooms')
                ->onDelete('cascade');
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');
            // Guest Info
            $table->string('guest_name');
            $table->string('guest_phone');
            $table->string('guest_cnic')->nullable();
            $table->string('guest_email')->nullable();
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);

            // Dates
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights');

            // Payment
            $table->decimal('room_price', 10, 2);             // price at time of booking
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['Pending', 'Paid', 'Partial', 'Refunded'])->default('Pending');
            $table->enum('payment_method', ['Cash', 'Card', 'Bank Transfer', 'JazzCash', 'EasyPaisa'])->default('Cash');
            $table->decimal('advance_paid', 10, 2)->default(0);

            // Booking Status
            $table->enum('status', ['Confirmed', 'Checked In', 'Checked Out', 'Cancelled', 'No Show'])->default('Confirmed');

            $table->text('special_requests')->nullable();
            $table->text('notes')->nullable();                // admin notes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
