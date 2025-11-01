<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sample_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->softDeletes();
            $table->timestamps();
        });
        
        // Configurar engine e charset para utf8mb3 conforme especificado
        DB::statement('ALTER TABLE sample_types ENGINE = InnoDB');
        DB::statement('ALTER TABLE sample_types CONVERT TO CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_types');
    }
};