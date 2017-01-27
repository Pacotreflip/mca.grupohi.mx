@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.origins')) }}
  <a href="{{ route('origenes.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_origin') }}</a>
</h1>
{!! Breadcrumbs::render('origenes.index') !!}
<hr>
<div class="table-responsive col-md-6 col-md-offset-3">
  <table class="table table-striped">
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
              <a href="{{ route('origenes.edit', [$origen]) }}" class="btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($origen->Estatus == 1)
              <a href="{{ route('origenes.destroy', [$origen]) }}" class="btn btn-danger btn-sm element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('origenes.destroy', [$origen]) }}" class="btn btn-success btn-sm element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop