@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.sindicatos')) }}
  <a href="{{ route('sindicatos.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_sindicato') }}</a>
  <a href="{{ route('csv.sindicatos') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('sindicatos.index') !!}
<hr>
<div class="table-responsive">
  <table class="table table-striped small">
    <thead>
      <tr>
        <th>ID Sindicato</th>
        <th>Descripción</th>
        <th>Nombre Corto</th>
        <th>Estatus</th>
        <th width="160px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($sindicatos as $sindicato)
        <tr>
          <td>
            <a href="{{ route('sindicatos.show', $sindicato) }}">#{{ $sindicato->IdSindicato }}</a>
          </td>
          <td>{{ $sindicato->Descripcion }}</td>
          <td>{{ $sindicato->NombreCorto }}</td>
          <td>{{ $sindicato->present()->estatus }}</td>
          <td>
              <a href="{{ route('sindicatos.edit', [$sindicato]) }}" class="btn btn-info btn-xs" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($sindicato->Estatus == 1)
              <a href="{{ route('sindicatos.destroy', [$sindicato]) }}" class="btn btn-danger btn-xs element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('sindicatos.destroy', [$sindicato]) }}" class="btn btn-success btn-xs element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop