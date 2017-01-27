@extends('layout')

@section('content')
<h1>ETAPAS DE PROYECTO
  <a href="{{ route('etapas.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nueva Etapa</a>
</h1>
{!! Breadcrumbs::render('etapas.index') !!}
<div class="table-responsive col-md-6 col-md-offset-3">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID Etapa</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($etapas as $etapa)
        <tr>
          <td>
            <a href="{{ route('etapas.show', $etapa) }}">#{{ $etapa->IdEtapaProyecto }}</a>
          </td>
          <td>{{ $etapa->Descripcion }}</td>
          <td>{{ $etapa->present()->estatus }}</td>
          <td>
              <a href="{{ route('etapas.edit', [$etapa]) }}" class="btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($etapa->Estatus == 1)
              <a href="{{ route('etapas.destroy', [$etapa]) }}" class="btn btn-danger btn-sm element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('etapas.destroy', [$etapa]) }}" class="btn btn-success btn-sm element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop