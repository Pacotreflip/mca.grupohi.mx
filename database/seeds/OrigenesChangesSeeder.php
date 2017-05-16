<?php

use Illuminate\Database\Seeder;

class OrigenesChangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('origenes')->update([
            'created_at'        => DB::raw("(SELECT CONCAT(FechaAlta, ' ', HoraAlta))"),
            'updated_at'        => DB::raw("(SELECT CONCAT(FechaAlta, ' ', HoraAlta))"),
        ]);
    }
}
