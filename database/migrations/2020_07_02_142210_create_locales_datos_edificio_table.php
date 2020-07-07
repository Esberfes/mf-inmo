<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalesDatosEdificioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locales_datos_edificio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_local');
            $table->mediumText('valor');
            $table->bigInteger('orden');
            $table->dateTime('creado_en', 0)->useCurrent();
            $table->dateTime('actualizado_en', 0)->useCurrent();
            $table->foreignId('id_usuario_actualizacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locales_datos_edificio');
    }
}
