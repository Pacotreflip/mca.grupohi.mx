<?php

namespace App\Http\Controllers;

use App\CSV\CSV;
use App\Models\Origen;
use App\Models\Ruta;
use Illuminate\Support\Facades\DB;

class CSVController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    public function rutas() {
        $headers = ['Clave', 'Origen', 'Tiro', 'Tipo de Ruta', '1er. KM', 'KM Subsecuentes', 'KM Adicionales', 'KM Total', 'Tiempo Minimo', 'Tiempo Tolerancia', 'Fecha y Hora Registro', 'Estatus', 'Registró'];
        $items = Ruta::join('origenes', 'rutas.IdOrigen', '=', 'origenes.IdOrigen')
            ->join('tiros', 'rutas.IdTiro', '=', 'tiros.IdTiro')
            ->join('tipo_ruta', 'rutas.IdTipoRuta', '=', 'tipo_ruta.IdTipoRuta')
            ->join('cronometrias', 'rutas.IdRuta', '=', 'cronometrias.IdRuta')
            ->join('igh.usuario', 'rutas.Registra', '=', 'igh.usuario.idusuario')
            ->select(DB::raw("CONCAT(rutas.Clave,rutas.IdRuta)"),
                "origenes.Descripcion as Origen",
                "tiros.Descripcion as Tiro",
                "tipo_ruta.Descripcion as Tipo",
                "rutas.PrimerKm",
                "rutas.KmSubsecuentes",
                "rutas.KmAdicionales",
                "rutas.TotalKM",
                "cronometrias.TiempoMinimo",
                "cronometrias.Tolerancia",
                DB::raw("CONCAT(rutas.FechaAlta, '', rutas.HoraAlta) as FechaHoraAlta"),
                "rutas.Estatus",
                DB::raw("CONCAT(igh.usuario.nombre, ' ', igh.usuario.apaterno, ' ', igh.usuario.amaterno)"))
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('rutas');
    }

    public function origenes() {
        $headers = ["Clave", "Tipo", "Descripción", "Fecha y Hora Registro", "Estatus"];
        $items = Origen::join('tiposorigenes', 'origenes.IdTipoOrigen', '=', 'tiposorigenes.IdTipoOrigen')
            ->select(
                DB::raw("CONCAT(origenes.Clave,origenes.IdOrigen)"),
                "tiposorigenes.Descripcion as Tipo",
                "origenes.Descripcion",
                DB::raw("CONCAT(origenes.FechaAlta, '', origenes.HoraAlta) as FechaHoraAlta"),
                "origenes.Estatus")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('origenes');
    }
}
