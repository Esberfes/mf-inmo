<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locales', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->unique();
            $table->string('telefono');
            $table->string('url_amigable')->unique();
            $table->decimal('precio', 12, 2)->nullable();
            $table->decimal('precio_alquiler', 12, 2)->nullable();
            $table->decimal('metros', 12, 2);
            $table->boolean('relevante')->default(0);
            $table->boolean('banner_activo')->default(0);
            $table->boolean('activo')->default(1);
            $table->mediumText('extracto');
            $table->mediumText('descripcion');
            $table->dateTime('creado_en', 0)->useCurrent();
            $table->dateTime('actualizado_en', 0)->useCurrent();
            $table->foreignId('id_usuario_actualizacion')->nullable();
            $table->foreignId('id_sector');
            $table->foreignId('id_poblacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locales');
    }
}
