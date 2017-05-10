@extends('layout')

@section('content')
<div class='success'></div>
<h1>{{ strtoupper(trans('strings.tarifas_material')) }}
    <a href="{{ route('tarifas_material.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nueva Tarifa</a>
    <a href="{{ route('csv.tarifas-material') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('tarifas_material.index') !!}
<hr>
<div class="errores"></div>
<div class="table-responsive">
    <table class="table table-hover table-bordered small">
        <thead>
            <tr>
                <th>Material</th>
                <th>Tarifa 1er. KM</th>
                <th>Tarifa KM Subsecuentes</th>
                <th>Tarifa KM Adicionales</th>
                <th>Inicio de Vigencia</th>
                <th>Fin de Vigencia</th>
                <th>Registro</th>
                <th>Fecha Hora Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tarifas as $tarifa)
            {!! Form::model($tarifa, ['class' => 'tarifa_create', 'method' => 'GET', 'route' => ['tarifas_material.edit', $tarifa]]) !!}
            @if($tarifa->FinVigenciaTarifa == 'VIGENTE')
            <tr style="background-color: azure">
                @else
                <tr>
                @endif
                <td>{{ $tarifa->material->Descripcion }}{!! Form::hidden('IdMaterial', $tarifa->material->IdMaterial) !!}</td>
                <td>{{ $tarifa->PrimerKM }}</td>
                <td>{{ $tarifa->KMSubsecuente }}</td>
                <td>{{ $tarifa->KMAdicional }}</td>
                <td>{{ $tarifa->InicioVigencia->format("d-m-Y h:i:s") }}</td>
                <td>{{ $tarifa->FinVigenciaTarifa }}</td>
                <td>{{ $tarifa->registro->present()->NombreCompleto }}</td>
                <td>{{ $tarifa->Fecha_Hora_Registra->format("d-m-Y h:i:s") }}</td>
            </tr>
            {!! Form::close() !!}
            @endforeach
        </tbody>
    </table>
</div>
@stop