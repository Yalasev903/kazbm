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
            $table->string('slug')->unique()->nullable()->after('id');
            $table->boolean('is_default')->default(false)->after('slug');
            $table->text('seo_title')->nullable()->after('region');
            $table->text('meta_description')->nullable()->after('seo_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->text('h1')->nullable()->after('meta_keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn(['slug','is_default','seo_title','meta_description','meta_keywords','h1']);
        });
    }
};
