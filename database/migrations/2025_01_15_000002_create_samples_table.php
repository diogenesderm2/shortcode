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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->string('sample_code')->unique();
            $table->foreignId('owner_id')->constrained('owners')->onDelete('cascade');
            $table->foreignId('father_id')->nullable()->constrained('animals')->onDelete('set null');
            $table->foreignId('mother_id')->nullable()->constrained('animals')->onDelete('set null');
            $table->foreignId('child_id')->constrained('animals')->onDelete('cascade');
            $table->enum('sample_type', ['sangue', 'pelo', 'saliva']);
            $table->date('collection_date');
            $table->text('observations')->nullable();
            $table->enum('status', ['pendente', 'processando', 'concluido'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};