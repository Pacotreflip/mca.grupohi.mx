<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 28/03/2017
 * Time: 05:44 PM
 */

namespace App\Models\Transformers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Themsaid\Transformers\AbstractTransformer;

class ViajeNetoReporteTransformer extends AbstractTransformer
{
    public static function toArray(Request $request, $horaInicial, $horaFinal, $estatus) {

        $timestamp_inicial = $request->get('FechaInicial') . ' ' . $horaInicial;
        $timestamp_final = $request->get('FechaFinal') . ' ' . $horaFinal;

        return DB::connection('sca')->select(DB::raw(
            "SELECT DATE_FORMAT(v.FechaLlegada, '%d-%m-%Y') AS Fecha,
      t.IdTiro,
      t.Descripcion AS Tiro,
      c.IdCamion AS IdCamion,
      c.Economico AS Camion,
      v.IdViajeNeto as IdViajeNeto,
      v.estatus as idEstatus,
      v.code,
      CASE 
        WHEN v.estatus in (1,11,21,31) THEN 'Validado'
        WHEN v.estatus in (0,10,20,30) THEN 'Pendiente de Validar'
      END AS Estatus,
      v.HoraLlegada as Hora,
      v.code,
      c.CubicacionParaPago as cubicacion,
      v.CubicacionCamion as CubicacionViajeNeto,
      vi.CubicacionCamion as CubicacionViaje,
      o.Descripcion as origen,
      o.IdOrigen as idorigen,
      m.Descripcion as material,
      m.IdMaterial as idmaterial,
      sin.Descripcion as Sindicato,
      sinca.Descripcion as SindicatoCamion,
      emp.razonSocial as Empresa,
      sincon.Descripcion as SindicatoConci,
      empcon.razonSocial as Empresaconci,
      TIMEDIFF( (CONCAT(v.FechaLlegada,' ',v.HoraLlegada)),(CONCAT(v.FechaSalida,' ',v.HoraSalida)) ) as tiempo_mostrar,
      ROUND((HOUR(TIMEDIFF(v.HoraLlegada,v.HoraSalida))*60)+(MINUTE(TIMEDIFF(v.HoraLlegada,v.HoraSalida)))+(SECOND(TIMEDIFF(v.HoraLlegada,v.HoraSalida))/60),2) AS tiempo,
      concat('R-',r.IdRuta) as ruta,
      r.TotalKM as distancia,
      r.IdRuta as idruta,
      tm.IdTarifa as tarifa_material,
      tm.PrimerKM as tarifa_material_pk,
      tm.KMSubsecuente as tarifa_material_ks,
      tm.KMAdicional as tarifa_material_ka,
      ((tm.PrimerKM*1*c.CubicacionParaPago)+(tm.KMSubsecuente*r.KmSubsecuentes*c.CubicacionParaPago)+(tm.KMAdicional*r.KmAdicionales*c.CubicacionParaPago)) as ImporteTotal_M,
      conci.idconciliacion,
      conci.fecha_conciliacion,
      conci.fecha_inicial,
      conci.fecha_final,
      conci.estado,
      vi.IdViaje,
      c.placas,
      c.PlacasCaja,
      v.CreoPrimerToque,
      v.Creo
      FROM
        viajesnetos AS v
      JOIN tiros AS t USING (IdTiro)
      JOIN camiones AS c USING (IdCamion)
      left join origenes as o using(IdOrigen) 
      join materiales as m using(IdMaterial) 
      left join tarifas as tm on(tm.IdMaterial=m.IdMaterial AND tm.Estatus=1) 
      left join rutas as r on(v.IdOrigen=r.IdOrigen AND v.IdTiro=r.IdTiro AND r.Estatus=1) 
      left join sindicatos as sinca on sinca.IdSindicato = c.IdSindicato
      LEFT JOIN viajes AS vi ON vi.IdViajeNeto = v.IdViajeNeto
      left join sindicatos as sin on sin.IdSindicato = vi.IdSindicato
      left join empresas as emp on emp.IdEmpresa = vi.IdEmpresa
      LEFT JOIN conciliacion_detalle AS conde ON conde.idviaje =  vi.IdViaje
      LEFT JOIN conciliacion as conci ON conci.idconciliacion = conde.idconciliacion 
      left join sindicatos as sincon on sincon.IdSindicato = conci.IdSindicato
      left join empresas as empcon on empcon.IdEmpresa = conci.IdEmpresa
      WHERE
      CAST(CONCAT(v.FechaLlegada,
                    ' ',
                    v.HoraLlegada)
            AS DATETIME) between '{$timestamp_inicial}' and '{$timestamp_final}'
      AND v.IdViajeNeto not in (select IdViajeNeto from viajesrechazados)
      AND v.Estatus {$estatus}
      group by IdViajeNeto
      ORDER BY v.FechaLlegada, camion, v.HoraLlegada, idEstatus;"));
    }
}