<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdImpresoraToTelefonos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telefonos', function (Blueprint $table) {
            $table->integer('id_impresora')->unsigned()->nullable();


            $table->foreign('id_impresora')
                ->references('id')
                ->on('impresoras')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telefonos', function (Blueprint $table) {
            $table->dropForeign('id_impresora');
            $table->dropColumn(['id_impresora']);
        });
    }
}
