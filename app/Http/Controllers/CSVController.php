<?php

namespace App\Http\Controllers;

use App\CSV\CSV;
use App\Models\Camion;
use App\Models\Material;
use App\Models\Origen;
use App\Models\Ruta;
use App\Models\Tiro;
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
        $items = Ruta::leftJoin('origenes', 'rutas.IdOrigen', '=', 'origenes.IdOrigen')
            ->leftJoin('tiros', 'rutas.IdTiro', '=', 'tiros.IdTiro')
            ->leftJoin('tipo_ruta', 'rutas.IdTipoRuta', '=', 'tipo_ruta.IdTipoRuta')
            ->leftJoin('cronometrias', 'rutas.IdRuta', '=', 'cronometrias.IdRuta')
            ->leftJoin('igh.usuario', 'rutas.Registra', '=', 'igh.usuario.idusuario')
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
        $items = Origen::leftJoin('tiposorigenes', 'origenes.IdTipoOrigen', '=', 'tiposorigenes.IdTipoOrigen')
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

    public function tiros() {
        $headers = ["Clave", "Descripción", "Fecha y Hora Registro", "Estatus"];
        $items = Tiro::select(
                DB::raw("CONCAT(tiros.Clave,tiros.IdTiro)"),
                "tiros.Descripcion",
                DB::raw("CONCAT(tiros.FechaAlta, '', tiros.HoraAlta) as FechaHoraAlta"),
                "tiros.Estatus")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('tiros');
    }

    public function camiones() {
        $headers = ["Economico", "Sindicato", "Empresa", "Placas del Camión", "Placas de la Caja", "Marca", "Modelo", "Propietario", "Operador", "Aseguradora", "Poliza de Seguro", "Vigencia Seguro", "Cubicación Real", "Cubicación Para Pago", "Estatus"];
        $items = Camion::leftJoin('igh.usuario', 'camiones.IdOperador', '=', 'igh.usuario.idusuario')
            ->leftJoin('sindicatos', 'camiones.IdSindicato', '=', 'sindicatos.IdSindicato')
            ->leftJoin('empresas', 'camiones.IdEmpresa', '=', 'empresas.IdEmpresa')
            ->leftJoin('marcas', 'camiones.IdMarca', '=', 'marcas.IdMarca')
            ->select(
                "camiones.Economico",
                "sindicatos.Descripcion as Sindicato",
                "empresas.razonSocial as Empresa",
                "camiones.Placas",
                "camiones.PlacasCaja",
                "marcas.Descripcion as Marca",
                "camiones.Modelo",
                "camiones.Propietario",
                DB::raw("CONCAT(igh.usuario.nombre, ' ', igh.usuario.apaterno, ' ', igh.usuario.amaterno)"),
                "camiones.Aseguradora",
                "camiones.PolizaSeguro",
                "camiones.VigenciaPolizaSeguro",
                "camiones.CubicacionReal",
                "camiones.CubicacionParaPago",
                "camiones.Estatus")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('camiones');
    }

    public function materiales() {
        $headers = ["ID", "Descripción", "Estatus"];
        $items = Material::select("IdMaterial", "Descripcion", "Estatus")->get();
        $csv = new CSV($headers, $items);
        $csv->generate('materiales');
    }
}
