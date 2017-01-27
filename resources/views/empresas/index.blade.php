@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.empresas')) }}
  <a href="{{ route('empresas.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_empresa') }}</a>
</h1>
{!! Breadcrumbs::render('empresas.index') !!}
<hr>
<div class="table-responsive col-md-6 col-md-offset-3">
  <table class="table table-striped">
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
              <a href="{{ route('empresas.edit', [$empresa]) }}" class="btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($empresa->Estatus == 1)
              <a href="{{ route('empresas.destroy', [$empresa]) }}" class="btn btn-danger btn-sm element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('empresas.destroy', [$empresa]) }}" class="btn btn-success btn-sm element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop