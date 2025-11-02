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
        Schema::table('genetic_results', function (Blueprint $table) {
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending')->after('genotype');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('review_notes')->nullable()->after('reviewed_at');
            $table->json('quality_metrics')->nullable()->after('review_notes'); // Para armazenar métricas de qualidade
            
            $table->index('status');
            $table->index('reviewed_by');
            $table->index('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('genetic_results', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['reviewed_by']);
            $table->dropIndex(['reviewed_at']);
            
            $table->dropColumn([
                'status',
                'reviewed_by',
                'reviewed_at',
                'review_notes',
                'quality_metrics'
            ]);
        });
    }
};