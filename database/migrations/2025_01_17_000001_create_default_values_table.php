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
        Schema::create('default_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_type')
                  ->constrained('billing_types')
                  ->onDelete('cascade');
            $table->decimal('amount', 65, 2);
            $table->softDeletes();
            $table->timestamps();
            
            // Índice para a chave estrangeira
            $table->index('billing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_values');
    }
};