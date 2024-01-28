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
        Schema::create('profiles_doctors_categories', function (Blueprint $table) {
            $table->foreignId('profile_doctor_id')->references('id')->on('profile_doctors')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->references('id')->on('categories')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles_doctors_categories');
    }
};
