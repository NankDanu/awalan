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
        Schema::create('cf_company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->unique();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable(); // Path to logo
            $table->string('favicon')->nullable(); // Path to favicon
            $table->string('login_background')->nullable(); // Path to login background
            $table->string('primary_color')->default('#3B82F6'); // Primary color for theme
            $table->string('secondary_color')->default('#10B981'); // Secondary color
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('company_name');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cf_company_settings');
    }
};
