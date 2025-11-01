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
        Schema::create('animal_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10);
            $table->softDeletes();
            $table->timestamps();
        });

        // Inserir tipos conforme especificado
        DB::table('animal_types')->insert([
            ['id' => 1, 'name' => 'Bovino', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Equino', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Ovino', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Caprino', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_types');
    }
};