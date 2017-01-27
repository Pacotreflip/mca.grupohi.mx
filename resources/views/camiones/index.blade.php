@extends('layout')

@section('content')
<h1>CAMIONES
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
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($camiones as $camion)
        <tr>
          <td>
                <a href="{{ route('camiones.show', $camion) }}">{{ $camion->Economico }}</a>
          </td>
          <td>{{ $camion->Propietario }}</td>
          <td>{{ isset($camion->operador->Nombre) ? $camion->operador->Nombre : 'SIN OPERADOR' }}</td>
          <td>{{ $camion->CubicacionReal}} m<sup>3</sup></td>
          <td>{{ $camion->CubicacionParaPago}} m<sup>3</sup></td>
          <td>{{ $camion->present()->estatus }}</td>
          <td>
              <a href="{{ route('camiones.edit', [$camion]) }}" class="btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($camion->Estatus == 1)
              <a href="{{ route('camiones.destroy', [$camion]) }}" class="btn btn-danger btn-sm element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('camiones.destroy', [$camion]) }}" class="btn btn-success btn-sm element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
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