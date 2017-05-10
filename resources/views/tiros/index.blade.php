@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.tiros')) }}
  <a href="{{ route('tiros.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_tiro') }}</a>
    <a href="{{ route('csv.tiros') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('tiros.index') !!}
<hr>
<div class="table-responsive">
  <table class="table table-striped small">
    <thead>
      <tr>
        <th>Clave</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($tiros as $tiro)
        <tr>
          <td>
            <a href="{{ route('tiros.show', $tiro) }}">{{ $tiro->present()->claveTiro }}</a>
          </td>
          <td>{{ $tiro->Descripcion }}</td>
          <td>{{ $tiro->present()->estatus }}</td>
          <td>
              <a href="{{ route('tiros.edit', [$tiro]) }}" class="btn btn-info btn-xs" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($tiro->Estatus == 1)
              <a href="{{ route('tiros.destroy', [$tiro]) }}" class="btn btn-danger btn-xs element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('tiros.destroy', [$tiro]) }}" class="btn btn-success btn-xs element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop