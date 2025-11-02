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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained('samples')->onDelete('cascade');
            $table->foreignId('dad_id')->nullable()->constrained('animals')->onDelete('cascade');
            $table->foreignId('mom_id')->nullable()->constrained('animals')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('test_types')->onDelete('cascade');
            $table->foreignId('user_registered')->nullable()->constrained('users');
            $table->string('responsible_collect', 191)->nullable();
            $table->integer('is_technique')->default(0);
            $table->integer('is_default')->default(0);
            $table->boolean('is_retest')->default(false);
            $table->text('central_result')->nullable();
            $table->text('comments')->nullable();
            $table->integer('is_read')->default(0);
            $table->softDeletes();
            $table->timestamps();

            // Índices para otimização
            $table->index('sample_id');
            $table->index('dad_id');
            $table->index('mom_id');
            $table->index('type_id');
            $table->index('user_registered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};