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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->string('arabic_name');
            $table->string('english_name');
            $table->string('mother_name');
            $table->string('passport_number')->unique();
            $table->date('dob');
            $table->date('passport_issue_date');
            $table->date('passport_expiry_date');
            $table->string('phone_number');
            $table->string('passport_photo')->nullable();
            $table->enum('type', ['adult', 'child']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};