@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.materials')) }}
  <a href="{{ route('materiales.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_material') }}</a>
    <a href="{{ route('csv.materiales') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('materiales.index') !!}
<hr>
<div class="table-responsive">
  <table class="table table-striped small">
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
              <a href="{{ route('materiales.edit', [$material]) }}" class="btn btn-info btn-xs" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($material->Estatus == 1)
              <a href="{{ route('materiales.destroy', [$material]) }}" class="btn btn-danger btn-xs element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('materiales.destroy', [$material]) }}" class="btn btn-success btn-xs element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop