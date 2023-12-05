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
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->dateTime('event_datetime');
            $table->dateTime('event_datetime_off');
            $table->enum('description', ['ON', 'OFF']);
            $table->enum('state', ['Active', 'In-Active'])->default('Active');
            $table->enum('status', ['In-Progress','Pending','Processing' ,'Finished'])->default('In-Progress');
            $table->enum('ACStat', ['Active', 'In-Active'])->default('In-Active');
            $table->enum('LightStat', ['Active', 'In-Active'])->default('In-Active');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule');
    }
};
