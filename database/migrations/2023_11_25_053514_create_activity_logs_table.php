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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('activity');
            $table->string('message');
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('device_id')->references('id')->on('device');
            $table->foreign('schedule_id')->references('id')->on('schedule');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
