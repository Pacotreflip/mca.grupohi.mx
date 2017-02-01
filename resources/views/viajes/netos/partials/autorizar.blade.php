<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.autorizar') !!}
<hr>
<div class="table-responsive col-md-10 col-md-offset-1">
    {!! Form::open(['id' => 'viaje_neto_autorizar' , 'method' => 'patch', 'route' => ['viajes.netos.autorizar']]) !!}
    <table id="viajes_netos_autorizar" class="table table-condensed">
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
                <td>{{ $viaje->Origen }}</td>
                <td>{{ $viaje->Material }}</td>
                <td>{{ $viaje->Observaciones }}</td>
                <td>
                    <input id="{{$viaje->IdViajeNeto}}" type="checkbox" value="20" name="Estatus[{{$viaje->IdViajeNeto}}]"/>
                </td>
                <td>
                    <input id="{{$viaje->IdViajeNeto}}" type="checkbox" value="22" name="Estatus[{{$viaje->IdViajeNeto}}]"/>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table> 
    <div class="form-group col-md-12" style="text-align: center; margin-top: 20px">         
        {!! Form::submit('Continuar', ['class' => 'btn btn-success']) !!}
    </div>
    {!! Form::close() !!}
</div>