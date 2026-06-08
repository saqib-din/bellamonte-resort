<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->boolean('has_vehicle')->default(false)->after('room_type');
            $table->string('vehicle_number')->nullable()->after('has_vehicle');   // LEA-1234
            $table->enum('vehicle_type', ['Car', 'SUV', 'Van', 'Bike', 'Jeep', 'Other'])
                ->nullable()->after('vehicle_number');
            $table->string('vehicle_model')->nullable()->after('vehicle_type');    // Corolla 2022
            $table->string('vehicle_color')->nullable()->after('vehicle_model');
            $table->string('driver_name')->nullable()->after('vehicle_color');
            $table->decimal('parking_charges', 10, 2)->default(0)->after('driver_name');

            $table->index('vehicle_number');
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropIndex(['vehicle_number']);
            $table->dropColumn([
                'has_vehicle',
                'vehicle_number',
                'vehicle_type',
                'vehicle_model',
                'vehicle_color',
                'driver_name',
                'parking_charges',
            ]);
        });
    }
};
