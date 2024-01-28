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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->foreignId('patient_id')->nullable()->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->foreignId('schedules_id')->nullable()->constrained()->references('id')->on('schedules')->onDelete('cascade')->comment('for master week_days');
            $table->foreignId('slot_id')->nullable()->constrained()->references('id')->on('doctors_availabilities')->onDelete('cascade')->comment('for master week_days');
            $table->dateTime('booking_datetime')->nullable();
            $table->string('total_patient')->nullable();
            $table->tinyInteger('status')->comment('1:Booked,2:Cancelled,3:Attended,4:Absent')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
