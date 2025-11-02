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
        Schema::create('genetic_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->enum('status', ['qualified', 'not_qualified', 'inconclusive', 'pending'])->default('pending');
            $table->enum('type', ['permanent_archive', 'parentage', 'paternity', 'maternity', 'general'])->default('general');
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->json('details')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('calculated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            // Indexes para performance
            $table->index(['test_id', 'status']);
            $table->index(['status', 'type']);
            $table->index('calculated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genetic_qualifications');
    }
};