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
        Schema::create('order_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('id_hash');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->on('site_users')
                ->references('id')
                ->cascadeOnDelete();

            $table->foreign('order_id')
                ->on('orders')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
