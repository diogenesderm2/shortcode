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
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registration')->nullable()->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_registered')->nullable();
            $table->unsignedBigInteger('user_updated')->nullable();
            $table->unsignedBigInteger('user_financial')->nullable();
            $table->unsignedBigInteger('user_representative')->nullable();
            $table->string('name', 60)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('adress_complement', 191);
            $table->string('adress_number', 191);
            $table->string('district', 60)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('rg', 10)->nullable();
            $table->string('cpf', 11)->nullable();
            $table->string('cnpj', 14)->nullable();
            $table->string('state_registration', 30)->nullable();
            $table->string('property', 100)->nullable();
            $table->integer('representative_percentage')->nullable();
            $table->text('comments')->nullable();
            $table->string('image', 64)->nullable();
            $table->integer('reteste_without_payment')->default(0);
            $table->integer('reteste_without_release')->default(0);
            $table->softDeletes();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_registered')->references('id')->on('users');
            $table->foreign('user_updated')->references('id')->on('users');
            $table->foreign('user_financial')->references('id')->on('users');
            $table->foreign('user_representative')->references('id')->on('users');
            
            // Índices
            $table->index('registration');
            $table->index('user_id');
            $table->index('user_registered');
            $table->index('user_updated');
            $table->index('user_financial');
            $table->index('user_representative');
        });
        
        // Configurar engine e charset para utf8mb3 conforme especificado
        DB::statement('ALTER TABLE owners ENGINE = InnoDB');
        DB::statement('ALTER TABLE owners CONVERT TO CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};