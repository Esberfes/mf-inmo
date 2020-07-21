<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalesDatosSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locales_datos_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_local');
            $table->string('nombre');
            $table->string('email');
            $table->string('telefono');
            $table->mediumText('comentario');
            $table->dateTime('creado_en', 0)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locales_datos_solicitudes');
    }
}
