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
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->nullOnDelete()->nullable();
            $table->foreignId('periodo_id')->constrained()->nullOnDelete()->nullable();
            $table->foreignId('turno_id')->constrained()->nullOnDelete()->nullable();
            $table->string('sigla');
            $table->string('ano');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};