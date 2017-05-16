<?php

use Illuminate\Database\Seeder;

class TirosChangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tiros')->update([
            'created_at'        => DB::raw("(SELECT CONCAT(FechaAlta, ' ', HoraAlta))"),
            'updated_at'        => DB::raw("(SELECT CONCAT(FechaAlta, ' ', HoraAlta))"),
        ]);
    }
}
