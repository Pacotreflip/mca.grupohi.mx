@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.destinos')) }}
  <a href="{{ route('destinos.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_destino') }}</a>
</h1>
{!! Breadcrumbs::render('destinos.index') !!}
<hr>
<div class="table-responsive col-md-6 col-md-offset-3">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Clave</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th width="160px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($destinos as $destino)
        <tr>
          <td>
            <a href="{{ route('destinos.show', $destino) }}">{{ $destino->present()->clave }}</a>
          </td>
          <td>{{ $destino->Descripcion }}</td>
          <td>{{ $destino->present()->estatus }}</td>
          <td>
              {!! link_to_route('destinos.edit', 'EDITAR', [$destino], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('destinos.destroy', 'ELIMINAR', [$destino], ['class' => 'btn btn-danger btn-sm destinos_destroy']) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop