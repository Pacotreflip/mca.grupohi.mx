@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.materials')) }}
  <a href="{{ route('materiales.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_material') }}</a>
</h1>
{!! Breadcrumbs::render('materiales.index') !!}
<hr>
<div class="table-responsive col-md-6 col-md-offset-3">
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
          <td>{{ $material->present()->estatus }}</td>
          <td>
              <a href="{{ route('materiales.edit', [$material]) }}" class="btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($material->Estatus == 1)
              <a href="{{ route('materiales.destroy', [$material]) }}" class="btn btn-danger btn-sm element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('materiales.destroy', [$material]) }}" class="btn btn-success btn-sm element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop