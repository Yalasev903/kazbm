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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('small_image');
            $table->string('image')->nullable();
            $table->string('date');
            $table->longText('body');
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('lang')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->smallInteger('is_popular')->default(0);
            $table->smallInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
