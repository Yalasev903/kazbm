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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('size_id')->nullable();
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('category_id');
            $table->integer('price');
            $table->integer('stock')->default(0);
            $table->string('photo');
            $table->string('slug')->unique()->nullable();
            $table->mediumText('description')->nullable();
            $table->json('data')->nullable();
            $table->json('galleries')->nullable();
            $table->json('parameters')->nullable();
            $table->smallInteger('per_piece')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->smallInteger('status')->default(0);
            $table->timestamps();

            $table->index(['size_id', 'color_id', 'category_id', 'title']);

            $table->foreign('size_id')
                ->on('product_sizes')
                ->references('id')
                ->cascadeOnDelete();

            $table->foreign('color_id')
                ->on('product_colors')
                ->references('id')
                ->cascadeOnDelete();

            $table->foreign('category_id')
                ->on('categories')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
