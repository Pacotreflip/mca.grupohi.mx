@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.camiones')) }}
  <a href="{{ route('camiones.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_camion') }}</a>
</h1>
{!! Breadcrumbs::render('camiones.index') !!}
<hr>
<div class="table-responsive col-md-10 col-md-offset-1">
  <table class="table table-hover table-bordered">
      <thead>
      <tr>
        <th style="text-align: center" colspan="3"></th>
        <th style="text-align: center" colspan="2" >Cubicación</th>
        <th colspan="2"></th>
      </tr>
      <tr>
        <th>Económico</th>
        <th>Propietario</th>
        <th>Operador</th>
        <th>Real</th>
        <th>Para Pago</th>
        <th>Estatus</th>
        <th width="160px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($camiones as $camion)
        <tr>
          <td>
                <a href="{{ route('camiones.show', $camion) }}">{{ $camion->Economico }}</a>
          </td>
          <td>{{ $camion->Propietario }}</td>
          <td>{{ $camion->operador->Nombre }}</td>
          <td>{{ $camion->CubicacionReal}} m<sup>3</sup></td>
          <td>{{ $camion->CubicacionParaPago}} m<sup>3</sup></td>
          <td>{{ $camion->present()->estatus }}</td>
          <td>
              {!! link_to_route('camiones.edit', trans('strings.edit'), [$camion], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('camiones.destroy', ($camion->Estatus == 1 ? trans('strings.delete') : trans('strings.activate')), [$camion], ['class' => 'btn ' . ($camion->Estatus == 1 ? 'btn-danger' : 'btn-success') . ' btn-sm camiones_destroy '.($camion->Estatus == 1 ? 'activo' : 'inactivo')]) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="text-center">
    {!! $camiones->render() !!}
  </div>
</div>
@stop