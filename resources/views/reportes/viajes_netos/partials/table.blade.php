<div class="table-responsive">
    <table class="table table-striped table-hover small">
        <thead>
        <tr>
            <th>#</th>
            <th>Creo Primer Toque</th>
            <th>Creo Segundo Toque</th>
            <th>Cubicación Camión (m3)</th>
            <th>Cubicación Viaje Neto (m3)</th>
            <th>Cubicación Viaje (m3)</th>
            <th>Camión</th>
            <th>Placas Camión</th>
            <th>Placas Caja</th>
            <th>Sindicato Camion</th>
            <th>Sindicato Viaje</th>
            <th>Empresa Viaje</th>
            <th>Sindicato Conciliado</th>
            <th>Empresa Conciliado</th>
            <th>Fecha Llegada</th>
            <th>Hora Llegada</th>
            <th>Turno</th>
            <th>Día de aplicación</th>
            <th>Orígen</th>
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
            <th>Folio Conciliación</th>
            <th>Fecha Conciliación</th>
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