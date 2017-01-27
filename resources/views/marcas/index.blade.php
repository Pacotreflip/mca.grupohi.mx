@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.brands')) }}
  <a href="{{ route('marcas.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_brand') }}</a>
</h1>
{!! Breadcrumbs::render('marcas.index') !!}
<hr>
<div class="table-responsive col-md-6 col-md-offset-3">
  <table class="table table-striped">
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
              <a href="{{ route('marcas.edit', [$marca]) }}" class="btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($marca->Estatus == 1)
              <a href="{{ route('marcas.destroy', [$marca]) }}" class="btn btn-danger btn-sm element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('marcas.destroy', [$marca]) }}" class="btn btn-success btn-sm element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop