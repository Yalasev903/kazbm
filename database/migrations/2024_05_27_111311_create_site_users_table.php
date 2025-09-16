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
        Schema::create('site_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('surname')->nullable();
            $table->string('patronymic')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->index();
            $table->smallInteger('status')->default(0);
            $table->string('password')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_users');
    }
};
