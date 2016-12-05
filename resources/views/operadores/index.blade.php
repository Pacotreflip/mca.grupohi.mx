@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.operadores')) }}
  <a href="{{ route('operadores.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_operador') }}</a>
</h1>
{!! Breadcrumbs::render('operadores.index') !!}
<hr>
<div class="table-responsive col-md-8 col-md-offset-2">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Dirección</th>
        <th>No. Licencia</th>
        <th>Vigencia Licencia</th>
        <th>Estatus</th>
        <th width="160px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($operadores as $operador)
        <tr>
          <td>
            <a href="{{ route('operadores.show', $operador) }}">#{{ $operador->IdOperador }}</a>
          </td>
          <td>{{ $operador->Nombre }}</td>
          <td>{{ $operador->Direccion }}</td>
          <td>{{ $operador->NoLicencia }}</td>
          <td>{{ $operador->VigenciaLicencia }}</td>
          <td>{{ $operador->present()->estatus }}</td>
          <td>
              {!! link_to_route('operadores.edit', 'EDITAR', [$operador], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('operadores.destroy', 'ELIMINAR', [$operador], ['class' => 'btn btn-danger btn-sm operadores_destroy']) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="text-center">
    {!! $operadores->render() !!}
  </div>
</div>
@stop