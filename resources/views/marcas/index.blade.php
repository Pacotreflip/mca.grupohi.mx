@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.brands')) }}
  <a href="{{ route('marcas.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_brand') }}</a>
    <a href="{{ route('csv.marcas') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>

</h1>
{!! Breadcrumbs::render('marcas.index') !!}
<hr>
<div class="table-responsive">
  <table class="table table-striped small">
    <thead>
      <tr>
        <th>ID Marca</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($marcas as $marca)
        <tr>
          <td>
            <a href="{{ route('marcas.show', $marca) }}">#{{ $marca->IdMarca }}</a>
          </td>
          <td>{{ $marca->Descripcion }}</td>
          <td>{{ $marca->present()->estatus }}</td>
          <td>
              <a href="{{ route('marcas.edit', [$marca]) }}" class="btn btn-info btn-xs" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($marca->Estatus == 1)
              <a href="{{ route('marcas.destroy', [$marca]) }}" class="btn btn-danger btn-xs element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('marcas.destroy', [$marca]) }}" class="btn btn-success btn-xs element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop