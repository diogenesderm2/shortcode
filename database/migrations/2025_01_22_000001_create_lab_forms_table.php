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
        Schema::create('lab_forms', function (Blueprint $table) {
            $table->id();
            $table->string('form_number', 50)->unique();
            $table->json('sample_positions'); // Armazena as posições das amostras no grid
            $table->json('form_data'); // Dados completos do formulário
            $table->foreignId('user_created')->constrained('users');
            $table->timestamp('generated_at');
            $table->timestamps();
            
            // Índices
            $table->index('form_number');
            $table->index('user_created');
            $table->index('generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_forms');
    }
};