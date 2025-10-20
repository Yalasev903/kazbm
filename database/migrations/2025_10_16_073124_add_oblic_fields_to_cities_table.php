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
        Schema::table('cities', function (Blueprint $table) {
            $table->json('oblic_seo_title')->nullable();
            $table->json('oblic_meta_description')->nullable();
            $table->json('oblic_meta_keywords')->nullable();
            $table->json('oblic_h1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->json('oblic_seo_title')->nullable();
            $table->json('oblic_meta_description')->nullable();
            $table->json('oblic_meta_keywords')->nullable();
            $table->json('oblic_h1')->nullable();
        });
    }
};
