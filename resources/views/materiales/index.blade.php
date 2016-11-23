@extends('layout')

@section('content')
  <h1>{{ strtoupper(trans('strings.materials')) }}
    <a href="{{ route('materiales.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_material') }}</a>
  </h1>
  {!! Breadcrumbs::render('materiales.index') !!}
  <hr>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>DESCRIPCION</th>
        <th>ESTATUS</th>
        <th>ACCIONES</th>
      </tr>
    </thead>
    <tbody>
      @foreach($materiales as $material)
        <tr>
          <td>
            <a href="{{ route('materiales.show', $material) }}">#{{ $material->IdMaterial }}</a>
          </td>
          <td>{{ $material->Descripcion }}</td>
          <td>{{ $material->estatus() }}</td>
          <td>
              {!! link_to_route('materiales.edit', 'EDITAR', [$material]) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop