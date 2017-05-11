<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelefonosImpresorasHistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telefonos_impresoras_historico', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_impresora')->unsigned();
            $table->integer('id_telefono')->unsigned();
            $table->integer('registro')->nullable();
            $table->integer('elimino')->nullable();
            $table->timestamps();

            $table->foreign('id_impresora')->references('id')->on('impresoras');
            $table->foreign('id_telefono')->references('id')->on('telefonos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('telefonos_impresoras_historico');
    }
}
