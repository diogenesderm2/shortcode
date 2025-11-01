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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('old_id')->nullable();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('billing_type');
            $table->unsignedBigInteger('animal_id');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('sample_type_id');
            $table->string('responsible_collect', 191)->nullable();
            $table->unsignedBigInteger('responsible_collect_id')->nullable();
            $table->unsignedBigInteger('user_registered')->nullable();
            $table->unsignedBigInteger('user_updated')->nullable();
            $table->unsignedBigInteger('user_released')->nullable();
            $table->unsignedBigInteger('user_representative')->nullable();
            $table->integer('is_technique')->default(0);
            $table->integer('is_default')->default(0);
            $table->integer('is_released')->default(0);
            $table->integer('priority')->default(0);
            $table->integer('show_client')->default(1);
            $table->integer('send_again')->default(0);
            $table->string('external_registry', 100)->nullable();
            $table->string('file_name', 50)->nullable();
            $table->decimal('value', 65, 2)->default(0.00);
            $table->double('representative_percentage')->nullable();
            $table->boolean('is_marked')->default(false);
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->timestamp('added_spreadsheet_at')->nullable();
            $table->timestamp('external_sample_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->timestamp('canceled_at')->nullable();
            $table->integer('is_sent')->default(0);

            // Índices
            $table->index('exam_id', 'samples_exam_id_foreign');
            $table->index('billing_type', 'samples_billing_type_foreign');
            $table->index('animal_id', 'samples_animal_id_foreign');
            $table->index('owner_id', 'samples_owner_id_foreign');
            $table->index('sample_type_id', 'samples_sample_type_id_foreign');
            $table->index('responsible_collect_id', 'samples_responsible_collect_id_foreign');
            $table->index('user_registered', 'samples_user_registered_foreign');
            $table->index('user_updated', 'samples_user_updated_foreign');
            $table->index('user_released', 'samples_user_released_foreign');
            $table->index('user_representative', 'user_representative');
            $table->index('created_at', 'idx_samples_created_at_month_year');
            $table->index('created_at', 'samples_created_at_index');
            $table->index('deleted_at', 'samples_deleted_at_index');

            // Foreign Keys
            $table->foreign('exam_id', 'samples_exam_id_foreign')->references('id')->on('exam_types');
            $table->foreign('billing_type', 'samples_billing_type_foreign')->references('id')->on('billing_types');
            $table->foreign('animal_id', 'samples_animal_id_foreign')->references('id')->on('animals');
            $table->foreign('owner_id', 'samples_owner_id_foreign')->references('id')->on('owners');
            $table->foreign('sample_type_id', 'samples_sample_type_id_foreign')->references('id')->on('sample_types');
            $table->foreign('responsible_collect_id', 'samples_responsible_collect_id_foreign')->references('id')->on('users');
            $table->foreign('user_registered', 'samples_user_registered_foreign')->references('id')->on('users');
            $table->foreign('user_updated', 'samples_user_updated_foreign')->references('id')->on('users');
            $table->foreign('user_released', 'samples_user_released_foreign')->references('id')->on('users');
            $table->foreign('user_representative', 'samples_ibfk_1')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
        });

        // Configurar engine e charset para utf8mb3 conforme especificado
        DB::statement('ALTER TABLE samples ENGINE = InnoDB');
        DB::statement('ALTER TABLE samples CONVERT TO CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci');
        DB::statement('ALTER TABLE samples AUTO_INCREMENT = 1848275');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};