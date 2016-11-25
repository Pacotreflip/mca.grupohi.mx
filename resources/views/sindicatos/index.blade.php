@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.sindicatos')) }}
  <a href="{{ route('sindicatos.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_sindicato') }}</a>
</h1>
{!! Breadcrumbs::render('sindicatos.index') !!}
<hr>
<div class="table-responsive col-md-6 col-md-offset-3">
  <table class="table table-striped">
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
              {!! link_to_route('sindicatos.edit', 'EDITAR', [$sindicato], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('sindicatos.destroy', 'ELIMINAR', [$sindicato], ['class' => 'btn btn-danger btn-sm sindicatos_destroy']) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop