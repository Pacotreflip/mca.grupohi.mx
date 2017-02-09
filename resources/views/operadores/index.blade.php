@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.operadores')) }}
  <a href="{{ route('operadores.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_operador') }}</a>
</h1>
{!! Breadcrumbs::render('operadores.index') !!}
<hr>
@include('partials.search-form')
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
        <th>Acciones</th>
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
              <a href="{{ route('operadores.edit', [$operador]) }}" class="btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($operador->Estatus == 1)
              <a href="{{ route('operadores.destroy', [$operador]) }}" class="btn btn-danger btn-sm operadores_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('operadores.destroy', [$operador]) }}" class="btn btn-success btn-sm operadores_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="text-center">
    {!! $operadores->appends(['buscar' => $busqueda])->render() !!}
  </div>
</div>
@stop