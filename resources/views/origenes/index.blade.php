@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.origins')) }}
  <a href="{{ route('origenes.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_origin') }}</a>
  <a href="{{ route('csv.origenes') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('origenes.index') !!}
<hr>
<div class="table-responsive">
  <table class="table table-striped small">
    <thead>
      <tr>
        <th>Clave</th>
        <th>Tipo</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th width="160px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($origenes as $origen)
        <tr>
          <td>
            <a href="{{ route('origenes.show', $origen) }}">{{ $origen->present()->claveOrigen }}</a>
          </td>
          <td>{{ $origen->tipoOrigen->Descripcion }}</td>
          <td>{{ $origen->Descripcion }}</td>
          <td>{{ $origen->present()->estatus }}</td>
          <td>
              @if($origen->Estatus == 1)
              <a href="{{ route('origenes.destroy', [$origen]) }}" class="btn btn-danger btn-xs element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('origenes.destroy', [$origen]) }}" class="btn btn-success btn-xs element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop