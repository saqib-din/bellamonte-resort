<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();       // INV-2024-0001
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');

            // Guest & Room Info
            $table->string('guest_name');
            $table->string('guest_phone')->nullable();
            $table->string('room_number')->nullable();
            $table->string('room_type')->nullable();
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->integer('nights')->default(1);

            // Charges
            $table->decimal('room_charges', 10, 2)->default(0);
            $table->decimal('extra_charges', 10, 2)->default(0);  // food, laundry etc
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            // Payment
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('balance_due', 10, 2)->default(0);
            $table->enum('payment_method', ['Cash', 'Card', 'Bank Transfer', 'JazzCash', 'EasyPaisa'])->default('Cash');
            $table->enum('status', ['Paid', 'Unpaid', 'Partial'])->default('Unpaid');

            $table->text('notes')->nullable();
            $table->date('issue_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
