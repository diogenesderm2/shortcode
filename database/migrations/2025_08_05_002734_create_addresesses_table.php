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
        Schema::create('addresesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('owners')->onDelete('cascade');
            $table->string('street', 100);
            $table->string('number', 20);
            $table->string('complement', 100)->nullable();
            $table->string('district', 60);
            $table->string('city', 60);
            $table->string('state', 2);
            $table->string('zip_code', 10);
            $table->string('reference_point', 100)->nullable();
            $table->boolean('is_main')->default(false);
            $table->boolean('is_billing')->default(false);
            $table->boolean('is_shipping')->default(false);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->foreignId('user_registered')->nullable()->constrained('users');
            $table->foreignId('user_updated')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresesses');
    }
};
