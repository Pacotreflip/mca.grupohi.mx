<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RutasTimestampsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rutas')->update([
            'created_at'        => DB::raw("(SELECT CONCAT(FechaAlta, ' ', HoraAlta))"),
            'updated_at'        => DB::raw("(SELECT CONCAT(FechaAlta, ' ', HoraAlta))"),
            'usuario_registro'  => DB::raw("(SELECT Registra)"),
            'usuario_desactivo' => DB::raw("(IF(Elimina is not null, (SELECT Elimina), null))")
        ]);
    }
}
