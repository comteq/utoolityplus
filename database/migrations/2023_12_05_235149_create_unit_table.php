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
         Schema::create('unit', function (Blueprint $table) {
            $table->id();
            $table->string('Pin_Num')->nullable();
            $table->string('Pin_Name')->nullable();
            $table->string('Status')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit', function (Blueprint $table) {
            $table->enum('AC', ['1', '0'])->default('0');
            $table->enum('Lights', ['1', '0'])->default('0');
            $table->dropColumn('Pin_Num');
            $table->dropColumn('Pin_Name');
            $table->dropColumn('Status');
            $table->dropColumn('updated_at');
        });
    }
};
