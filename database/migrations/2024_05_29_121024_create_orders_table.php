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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('delivery')->default('self');
            $table->smallInteger('payment')->default(0);
            $table->smallInteger('ip_account')->default(0);
            $table->string('city');
            $table->string('street');
            $table->string('house')->nullable();
            $table->smallInteger('status')->default(0);
            $table->json('products')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
