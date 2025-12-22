<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('city_section_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('section');
            $table->json('content')->nullable();
            $table->timestamps();
            $table->unique(['city_id', 'section']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('city_section_contents');
    }
};
