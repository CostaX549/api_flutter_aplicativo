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
        Schema::create('working_hours', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger("doc_id"); 
            $table->unsignedBigInteger('day'); // 'Segunda', 'Terça', etc.
            $table->time('start'); // Horário de início
            $table->time('end'); // Horário de término
            $table->time('interval_start')->nullable(); // Horário de início do intervalo
            $table->time('interval_end')->nullable(); // Horário de fim do intervalo
            $table->foreign("doc_id")->references("id")->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_hours');
    }
};
