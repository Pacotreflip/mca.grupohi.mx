@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.centroscostos')) }}
  <a href="{{ route('centroscostos.create', 0) }}" class="btn btn-success pull-right centrocosto_create"><i class="fa fa-plus"></i> NUEVO CENTRO DE COSTO</a>
    <a href="{{ route('csv.centros-costos') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>

</h1>
{!! Breadcrumbs::render('centroscostos.index') !!}
<hr>
<div class="table-responsive">
    <table id='centros_costos_table' class="table table-hover small">
        <thead>
            <tr>
                <th>Centro de Costo</th>
                <th>Cuenta</th>
                <th>Acciones</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($centros as $centro)
            @if($centro->IdPadre == 0)
            <tr id="{{$centro->IdCentroCosto}}" class="treegrid-{{$centro->IdCentroCosto}}">
            @else
            <tr id="{{$centro->IdCentroCosto}}" class="treegrid-{{$centro->IdCentroCosto}} treegrid-parent-{{$centro->IdPadre}}">
            @endif
                <td>{{$centro->Descripcion}}</td>
                <td>{{$centro->Cuenta}}</td>
                <td>
                    <a href="{{ route('centroscostos.edit', $centro) }}" class="btn btn-info btn-xs centrocosto_edit" type="button">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>
                    <a href="{{ route('centroscostos.create', $centro) }}" class="btn btn-success btn-xs centrocosto_create" type="button">
                        <i class="fa fa-plus-circle"></i>
                    </a>
                    <a href="{{ route('centroscostos.destroy', $centro) }}" class="btn btn-danger btn-xs centrocosto_destroy" type="button">
                        <i class="fa fa-minus-circle"></i>
                    </a>
                </td>
                <td>
                    <a href="{{ route('centroscostos.destroy', $centro) }}" class="btn btn-xs centrocosto_toggle {{ $centro->Estatus == 1 ? 'activo btn-danger' : 'inactivo btn-success' }}">
                        {{ $centro->Estatus == 1 ? trans('strings.deactivate') : trans('strings.activate') }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>             
    </table>
</div>
<div id="div_modal"></div>
@stop

