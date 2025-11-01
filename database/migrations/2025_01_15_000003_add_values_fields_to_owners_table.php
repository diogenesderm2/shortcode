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
        Schema::table('owners', function (Blueprint $table) {
            $table->decimal('valor_identificacao_genetica_bovino', 10, 2)->nullable()->after('comments');
            $table->decimal('valor_dna_caseira_bovino', 10, 2)->nullable()->after('valor_identificacao_genetica_bovino');
            $table->decimal('valor_kappa_caseina_bovino', 10, 2)->nullable()->after('valor_dna_caseira_bovino');
            $table->decimal('valor_beta_lactoglobulina_bovino', 10, 2)->nullable()->after('valor_kappa_caseina_bovino');
            $table->decimal('valor_identificacao_genetica_equino', 10, 2)->nullable()->after('valor_beta_lactoglobulina_bovino');
            $table->decimal('valor_identificacao_genetica_caprino', 10, 2)->nullable()->after('valor_identificacao_genetica_equino');
            $table->decimal('valor_hvip_equino', 10, 2)->nullable()->after('valor_identificacao_genetica_caprino');
            $table->decimal('valor_five_panel_obed_hibrida_hty_equino', 10, 2)->nullable()->after('valor_hvip_equino');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropColumn([
                'valor_identificacao_genetica_bovino',
                'valor_dna_caseira_bovino',
                'valor_kappa_caseina_bovino',
                'valor_beta_lactoglobulina_bovino',
                'valor_identificacao_genetica_equino',
                'valor_identificacao_genetica_caprino',
                'valor_hvip_equino',
                'valor_five_panel_obed_hibrida_hty_equino'
            ]);
        });
    }
};