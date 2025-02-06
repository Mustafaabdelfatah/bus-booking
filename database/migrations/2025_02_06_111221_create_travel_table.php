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
        Schema::create('travel', function (Blueprint $table) {
            $table->id();
        //    $table->foreignId('bus_id')->constrained('logic_tasks')->cascadeOnDelete();

            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->string('departure_station');
            $table->string('arrival_station');
            $table->dateTime('departure_time');
            $table->integer('available_seats');
            $table->decimal('ticket_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel');
    }
};
