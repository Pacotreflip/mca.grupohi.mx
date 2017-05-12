<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMotivoToTelefonosImpresorasHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telefonos_impresoras_historico', function (Blueprint $table) {
            $table->text('motivo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telefonos_impresoras_historico', function (Blueprint $table) {
            $table->dropColumn(['motivo']);
        });
    }
}
