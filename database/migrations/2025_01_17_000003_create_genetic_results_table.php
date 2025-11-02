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
        Schema::create('genetic_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained('samples')->onDelete('cascade');
            $table->foreignId('marker_id')->constrained('genetic_markers')->onDelete('cascade');
            $table->string('allele_1')->nullable();
            $table->string('allele_2')->nullable();
            $table->string('genotype')->nullable(); // Combinação dos alelos (ex: AA, AC, CC)
            $table->timestamps();
            
            $table->index('sample_id');
            $table->index('marker_id');
            $table->unique(['sample_id', 'marker_id']); // Evita duplicatas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genetic_results');
    }
};