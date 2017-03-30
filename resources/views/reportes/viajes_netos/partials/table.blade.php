<div class="table-responsive">
    <table class="table table-striped">
        <tr>
            <td colspan="32"><div align="right"><font color="#000000" style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px;"><?PHP echo 'FECHA DE CONSULTA '.date("d-m-Y")."/".date("H:i:s",time()); ?></font></div></td>
        </tr>
        <tr>
            <td colspan="32"  align="center">
                <div align="left">
                    <font color="#000000" style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px;">VIAJES NETOS DEL PERIODO (</font>
                    <font color="#666666" style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px;"><?PHP echo $request['FechaInicial'] . ' ' . $request['HoraInicial']; ?></font>
                    <font color="#000000" style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px;"> AL </font><font color="#666666" style="font-family:'Trebuchet MS'; font-weight:bold;font-size:14px;"><?PHP echo $request['FechaFinal'] . ' ' . $request['HoraFinal']; ?>)</font></div></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2"><font color="#000000" face="Trebuchet MS" style="font-size:12px; ">OBRA:</font>&nbsp;<font color="#666666" face="Trebuchet MS" style="font-size:12px; "><?php echo \App\Models\Proyecto::find(\App\Facades\Context::getId())->descripcion ?></font></td>
        </tr>

        <tr>
            <td colspan="2"><font color="#000000" face="Trebuchet MS" style="font-size:12px; ">FECHA:</font> &nbsp;<font color="#666666" face="Trebuchet MS" style="font-size:12px; "><?php echo date("d-m-Y"); ?></font></td>
        </tr>
    </table>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped table-hover small">
        <thead>
        <tr>
            <th>#</th>
            <th>Creo Primer Toque</th>
            <th>Creo Segundo Toque</th>
            <th>Cubicaci&oacute;n Cami&oacute;n m<sup>3</sup></th>
            <th>Cubicaci&oacute;n Viaje Neto m<sup>3</sup></th>
            <th>Cubicaci&oacute;n Viaje m<sup>3</sup></th>
            <th>Cami&oacute;n</th>
            <th>Placas Cami&oacute;n</th>
            <th>Placas Caja</th>
            <th>Sindicato Camion</th>
            <th>Sindicato Viaje</th>
            <th>Empresa Viaje</th>
            <th>Sindicato Conciliado</th>
            <th>Empresa Conciliado</th>
            <th>Fecha Llegada</th>
            <th>Hora Llegada</th>
            <th>Turno</th>
            <th>D&iacute;a de aplicaci&oacute;n</th>
            <th>Or&iacute;gen</th>
            <th>Destino</th>
            <th>Material</th>
            <th>Tiempo</th>
            <th>Ruta</th>
            <th>Distancia (Km)</th>
            <th>1er Km</th>
            <th>Km Sub.</th>
            <th>Km Adc.</th>
            <th>Importe</th>
            <th>Estatus</th>
            <th>Ticket</th>
            <th>Folio Conciliaci&oacute;n</th>
            <th>Fecha Conciliaci&oacute;n</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key => $item)
            <?php
            $creo_1 = App\User::find($item->CreoPrimerToque) ? App\User::find($item->CreoPrimerToque)->present()->nombreCompleto : '';
            $creo_2 = App\User::find($item->Creo) ? App\User::find($item->Creo)->present()->nombreCompleto : '';
            $horaini = '07:00:00';
            $horafin = '19:00:00';
            $turno = ($item->Hora >= $horaini && $item->Hora < $horafin) ? 'Primer Turno' : 'Segundo Turno';
            if($item->Hora >= '00:00:00' && $item->Hora < $horaini){
                $fechaAplica = strtotime ( '-1 day' , strtotime ( $item->Fecha ) ) ;
                $fechaAplica = date ( 'd-m-Y' , $fechaAplica );
            }
            else {
                $fechaAplica = $item->Fecha;
            }
            ?>
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $creo_1 }}</td>
                <td>{{ $creo_2 }}</td>
                <td>{{ $item->cubicacion }}</td>
                <td>{{ $item->CubicacionViajeNeto }}</td>
                <td>{{ $item->CubicacionViaje }}</td>
                <td>{{ $item->Camion }}</td>
                <td>{{ $item->placas }}</td>
                <td>{{ $item->PlacasCaja }}</td>
                <td>{{ $item->SindicatoCamion }}</td>
                <td>{{ $item->Sindicato }}</td>
                <td>{{ $item->Empresa }}</td>
                <td>{{ $item->SindicatoConci }}</td>
                <td>{{ $item->Empresaconci }}</td>
                <td>{{ $item->Fecha }}</td>
                <td>{{ $item->Hora }}</td>
                <td>{{ $turno }}</td>
                <td>{{ $fechaAplica }}</td>
                <td>{{ $item->origen }}</td>
                <td>{{ $item->Tiro }}</td>
                <td>{{ $item->material }}</td>
                <td>{{ $item->tiempo_mostrar }}</td>
                <td>{{ $item->ruta }}</td>
                <td>{{ $item->distancia }}</td>
                <td>{{ number_format($item->tarifa_material_pk,2,".",",") }}</td>
                <td>{{ number_format($item->tarifa_material_ks,2,".",",") }}</td>
                <td>{{ number_format($item->tarifa_material_ka,2,".",",") }}</td>
                <td>{{ number_format($item->ImporteTotal_M,2,".",",") }}</td>
                <td>{{ $item->Estatus }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->idconciliacion }}</td>
                <td>{{ $item->fecha_conciliacion }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>