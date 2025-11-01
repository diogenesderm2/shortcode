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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('old_id')->nullable();
            $table->foreignId('animal_type')->default(1)->constrained('animal_types')->onDelete('cascade');
            $table->foreignId('breed_id')->constrained('breeds');
            $table->string('protocol', 20)->nullable();
            $table->string('name', 120);
            $table->string('register', 550)->nullable();
            $table->integer('genre')->default(0)->comment('0: indefinido, 1: Macho, 2: Femea');
            $table->date('birth')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['animal_type']);
            $table->index(['breed_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};