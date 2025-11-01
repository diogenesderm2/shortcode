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
        // Esta migration foi desabilitada para evitar duplicação
        // A tabela exam_types é criada no arquivo 2025_01_14_000005_create_samples_table.php
        // A tabela billing_types é criada no arquivo 2025_01_14_000008_create_samples_table.php
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nada para reverter
    }
};