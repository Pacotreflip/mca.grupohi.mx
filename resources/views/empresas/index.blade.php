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
              {!! link_to_route('empresas.edit', 'EDITAR', [$empresa], ['class' => 'btn btn-warning btn-sm']) !!}
              {!! link_to_route('empresas.destroy', 'ELIMINAR', [$empresa], ['class' => 'btn btn-danger btn-sm empresas_destroy']) !!}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop