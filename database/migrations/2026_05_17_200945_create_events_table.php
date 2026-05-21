<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tag');
            $table->string('image');           // stored path
            $table->date('event_date');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('detail_image_1')->nullable();
            $table->string('detail_image_2')->nullable();
            $table->string('detail_image_3')->nullable();
            $table->string('section_1_title')->nullable();
            $table->text('section_1_text')->nullable();
            $table->string('section_2_title')->nullable();
            $table->text('section_2_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
