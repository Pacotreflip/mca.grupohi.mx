@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.empresas')) }}
  <a href="{{ route('empresas.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_empresa') }}</a>
  <a href="{{ route('csv.empresas') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('empresas.index') !!}
<hr>
<div class="table-responsive">
  <table class="table table-striped small">
    <thead>
      <tr>
        <th>ID Empresa</th>
        <th>Razón Social</th>
        <th>RFC</th>
        <th>Estatus</th>
        <th width="160px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($empresas as $empresa)
        <tr>
          <td>
            <a href="{{ route('empresas.show', $empresa) }}">#{{ $empresa->IdEmpresa }}</a>
          </td>
          <td>{{ $empresa->razonSocial }}</td>
          <td>{{ $empresa->RFC }}</td>
          <td>{{ $empresa->present()->estatus }}</td>
          <td>
              <a href="{{ route('empresas.edit', [$empresa]) }}" class="btn btn-info btn-xs" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($empresa->Estatus == 1)
              <a href="{{ route('empresas.destroy', [$empresa]) }}" class="btn btn-danger btn-xs element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('empresas.destroy', [$empresa]) }}" class="btn btn-success btn-xs element_destroy inactivo" title="Habilitar"><i class="fa fa-plus"></i></a>
              @endif
          </td>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop