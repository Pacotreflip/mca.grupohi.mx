@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.tiros')) }}
  <a href="{{ route('tiros.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_tiro') }}</a>
</h1>
{!! Breadcrumbs::render('tiros.index') !!}
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
      @foreach($tiros as $tiro)
        <tr>
          <td>
            <a href="{{ route('tiros.show', $tiro) }}">{{ $tiro->present()->claveTiro }}</a>
          </td>
          <td>{{ $tiro->Descripcion }}</td>
          <td>{{ $tiro->present()->estatus }}</td>
          <td>
              {!! link_to_route('tiros.edit', 'EDITAR', [$tiro], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('tiros.destroy', 'ELIMINAR', [$tiro], ['class' => 'btn btn-danger btn-sm tiros_destroy']) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop