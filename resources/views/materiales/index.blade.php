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
        <th>ID Material</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th>Acciones</th>
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
              {!! Form::open(['route' => ['materiales.destroy', $material], 'method' => 'delete']) !!}
              {!! link_to_route('materiales.edit', 'EDITAR', [$material], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! Form::submit('ELIMINAR', ['class' => 'btn btn-danger btn-sm']) !!}
              {!! Form::close() !!}          
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop