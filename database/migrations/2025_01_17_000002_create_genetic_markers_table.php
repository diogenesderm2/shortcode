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
        Schema::create('genetic_markers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('chromosome')->nullable();
            $table->bigInteger('position')->nullable();
            $table->string('ref_allele')->nullable();
            $table->string('alt_allele')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('name');
            $table->index(['chromosome', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genetic_markers');
    }
};