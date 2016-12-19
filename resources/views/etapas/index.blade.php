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
        <th width="160px">Acciones</th>
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
              {!! link_to_route('etapas.edit', 'EDITAR', [$etapa], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('etapas.destroy', 'ELIMINAR', [$etapa], ['class' => 'btn btn-danger btn-sm etapas_destroy']) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop