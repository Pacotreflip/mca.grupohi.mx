<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.validar') !!}
<hr>
<div class="text-right">
  {!! Form::model(Request::only(['FechaInicial', 'FechaFinal', 'action']), ['method' => 'GET', 'class' => 'form-inline']) !!}
    <div class="input-group">
        {!! Form::text('FechaInicial', null, ['class' => 'fecha input-sm', 'placeholder' => 'Fecha Inicial...', 'value' => \Carbon\Carbon::now()->toDateString()]) !!}
        {!! Form::text('FechaFinal', null, ['class' => 'fecha input-sm', 'placeholder' => 'Fecha Final...']) !!}
        <input type="hidden" name="action" value="validar">
      <span class="input-group-btn">
        <button class="btn btn-sm btn-primary" type="submit">Consultar</button>
      </span>
    </div>
  {!! Form::close() !!}
  <br>
</div>
            
<div class="table-responsive col-md-10 col-md-offset-1">
    <table id="viajes_netos_validar" class="table table-hover">
        <thead>
            <tr>
                <th>Fecha Llegada</th>
                <th>Hora Llegada</th>
                <th>Camión</th>
                <th>Tiro</th>
                <th>Origen</th>
                <th>Material</th>
                <th>m<sup>3</sup></th>
            </tr>
        </thead>
        <tbody>
            @foreach($viajes as $viaje)
            <tr>
                <td>{{ $viaje->FechaLlegada }}</td>
                <td>{{ $viaje->HoraLlegada }}</td>
                <td>{{ $viaje->camion->Economico }}</td>
                <td>{{ $viaje->tiro->Descripcion }}</td>
                <td>{{ $viaje->origen->Descripcion }}</td>
                <td>{{ $viaje->material->Descripcion }}</td>
                <td>gg</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>