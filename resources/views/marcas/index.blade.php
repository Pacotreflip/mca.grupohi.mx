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
        <th width="160px">Acciones</th>
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
              {!! link_to_route('marcas.edit', 'EDITAR', [$marca], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('marcas.destroy', 'ELIMINAR', [$marca], ['class' => 'btn btn-danger btn-sm marcas_destroy']) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop