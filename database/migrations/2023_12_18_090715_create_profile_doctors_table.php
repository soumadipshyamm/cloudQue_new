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
        Schema::create('profile_doctors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->unsignedBigInteger('categorie_id');
            $table->foreign('categorie_id')->nullable()->references('id')->on('categories')->onDelete('cascade');
            $table->enum('type', ['doctor'])->nullable();
            $table->string('qualifaction')->nullable()->comment('doctor');
            $table->date('registration_date')->nullable()->comment('doctor');
            $table->string('registration_number')->nullable()->comment('doctor');
            $table->string('description')->nullable();
            $table->string('experience')->nullable()->comment('doctor');
            $table->string('consultation_fee')->nullable()->comment('doctor');
            $table->string('price')->nullable()->comment('doctor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_doctors');
    }
};
