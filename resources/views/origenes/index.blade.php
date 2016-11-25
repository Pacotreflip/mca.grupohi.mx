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
            <a href="{{ route('origenes.show', $origen) }}">{{ $origen->present()->clave }}</a>
          </td>
          <td>{{ $origen->tipoOrigen->Descripcion }}</td>
          <td>{{ $origen->Descripcion }}</td>
          <td>{{ $origen->present()->estatus }}</td>
          <td>
              {!! link_to_route('origenes.edit', 'EDITAR', [$origen], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('origenes.destroy', 'ELIMINAR', [$origen], ['class' => 'btn btn-danger btn-sm origenes_destroy']) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop