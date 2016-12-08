@extends('layout')

@section('styles')
<link rel="stylesheet" href="http://maxazan.github.io/jquery-treegrid/css/jquery.treegrid.css">
@stop
@section('content')
<h1>{{ strtoupper(trans('strings.centroscostos')) }}</h1>
{!! Breadcrumbs::render('centroscostos.index') !!}
<hr>
<div class="table-responsive col-md-8 col-md-offset-2">
    <table id='centros_costos_table' class="table table-hover">
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
                    <a href="{{ route('centroscostos.create', $centro) }}" class="btn btn-success btn-xs centrocosto_create" type="button">
                        <i class="fa fa-plus-circle"></i>
                    </a>
                </td>
                <td>{{$centro->present()->Estatus}}</td>
            </tr>
            @endforeach
        </tbody>             
    </table>
</div>
<div id="div_modal"></div>
@stop