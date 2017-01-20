<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.validar') !!}
<hr>
<div class="table-responsive col-md-10 col-md-offset-1 rcorners">
    {!! Form::open(['id' => 'viaje_neto_validar' , 'method' => 'patch', 'route' => ['viajes.netos.validar']]) !!}
    <table id="viajes_manual_autorizar" class="table table-hover">
        <thead>
            <tr>
                <th>Fecha Llegada</th>
                <th>Hora Llegada</th>
                <th>Camión</th>
                <th>Tiro</th>
                <th>Origen</th>
                <th>Material</th>
                <th>Observaciones</th>
                <th><i style="color: green" class="fa fa-check"></i></th>
                <th><i style="color: red" class="fa fa-remove"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach($viajes as $viaje)
            <tr>
                <td>{{ $viaje->FechaLlegada }}</td>
                <td>{{ $viaje->HoraLlegada }}
                <td>{{ $viaje->Camion }}</td>
                <td>{{ $viaje->Tiro }}</td>
                <td>{{ $viaje->Origen }}
                <td>{{ $viaje->Material }}
                <td>{{ $viaje->Observaciones }}
                <td><input id="{{$viaje->IdViajeNeto}}" type="checkbox" value="20" name="Estatus[{{$viaje->IdViajeNeto}}]"/></td>
                <td><input id="{{$viaje->IdViajeNeto}}" type="checkbox" value="22" name="Estatus[{{$viaje->IdViajeNeto}}]"/></td>
            </tr>
            @endforeach
        </tbody>
    </table> 
    <div class="form-group col-md-12" style="text-align: center; margin-top: 20px">         
        {!! Form::submit('Continuar', ['class' => 'btn btn-success']) !!}
    </div>
    {!! Form::close() !!}
</div>