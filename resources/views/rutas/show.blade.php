@extends('layout')

@section('content')
<h1>{{ $ruta->present()->claveRuta }}
    <a href="{{ route('rutas.edit', $ruta) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit') }}</a>
    <a href="{{ route('rutas.destroy', $ruta) }}" class="btn pull-right rutas_destroy {{ $ruta->Estatus == 1 ? 'activo btn-danger' : 'inactivo btn-success' }}" style="margin-right: 5px"><i class="fa {{ $ruta->Estatus == 1 ? 'fa-close' : 'fa-plus' }}"></i> {{ $ruta->Estatus == 1 ? trans('strings.delete') : trans('strings.activate') }}</a>
</h1>
{!! Breadcrumbs::render('rutas.show', $ruta) !!}
<hr>
{!! Form::model($ruta) !!}
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('Origen', 'Origen', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('Origen', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Tiro', 'Tiro', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('Tiro', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('TipoRuta','Tipo de Ruta', ['class' => 'control-label col-sm-2'])  !!}
            <div class="col-sm-4">
                {!! Form::text('TipoRuta', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>     
            @if($ruta->archivo)
            {!! Form::label('Croquis', 'Archivo de Croquis', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                <a class="btn btn-info col-md-12" href="{{ URL::to('/').'/'.$ruta->archivo->Ruta }}" target="blank">
                    <i class="fa fa-file-{{$ruta->archivo->Tipo == 'application/pdf' ? 'pdf' : 'image'}}-o"></i> VER ARCHIVO                  
                </a>  
            </div>
            @endif
        </div>
    </fieldset>
</div>
<div style="margin-top: 20px" class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-tachometer"></i> Kilometraje</legend>
        <div class="form-group">
            {!! Form::label('PrimerKm', 'Primer KM', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('PrimerKm', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('KmSubsecuentes', 'KM Subsecuentes', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('KmSubsecuentes', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('KmAdicionales', 'KM Adicionales', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('KmAdicionales', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('TotalKM', 'KM Total', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('TotalKM', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
    </fieldset>    
</div>
{!! Form::close() !!}
{!! Form::model($ruta->cronometria) !!}
<div style="margin-top: 20px" class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-clock-o"></i> Cronometría</legend>
        <div class="form-group">
            {!! Form::label('TiempoMinimo', 'Tiempo Mínimo (min)', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('TiempoMinimo', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Tolerancia', 'Tolerancia (min)', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('Tolerancia', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        </legend>
    </fieldset>
</div>
{!! Form::close() !!}
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('rutas.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
@stop