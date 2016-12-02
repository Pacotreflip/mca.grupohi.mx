@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.new_ruta')) }}</h1>
{!! Breadcrumbs::render('rutas.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'rutas.store', 'files' => true]) !!}
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('IdOrigen', 'Origen', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                {!! Form::select('IdOrigen', $origenes, null, ['placeholder' => 'Seleccione un Origen...', 'class' => 'form-control']) !!}
            </div>
            {!! Form::label('IdTiro', 'Tiro', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                {!! Form::select('IdTiro', $tiros, null, ['placeholder' => 'Seleccione un Tiro...', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('IdTipoRuta','Tipo de Ruta', ['class' => 'control-label col-sm-2'])!!}
            <div class="col-sm-10">
                {!! Form::select('IdTipoRuta', $tipos, null, ['placeholder' => 'Seleccione un Tipo...', 'class' => 'form-control']) !!}
            </div>
        </div>
    </fieldset>
</div>
<div style="margin-top: 20px" class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-tachometer"></i> Kilometraje</legend>
        <div class="form-group">
            {!! Form::label('PrimerKm', 'Primer KM', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('PrimerKm', 1, ['class' => 'form-control km', 'readonly' => 'readonly']) !!}
            </div>
            {!! Form::label('KmSubsecuentes', 'KM Subsecuentes', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::text('KmSubsecuentes', '0', ['class' => 'form-control km']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('KmAdicionales', 'KM Adicionales', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::text('KmAdicionales', '0', ['class' => 'form-control km']) !!}
            </div>
            {!! Form::label('TotalKM', 'KM Total', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('TotalKM', null, ['class' => 'form-control totalKm', 'readonly' => 'readonly']) !!}
            </div>
        </div>
    </fieldset>    
</div>
<div style="margin-top: 20px" class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-clock-o"></i> Cronometría</legend>
        <div class="form-group">
            {!! Form::label('TiempoMinimo', 'Tiempo Mínimo (min)', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('TiempoMinimo', '0', ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('Tolerancia', 'Tolerancia (min)', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('Tolerancia', '0', ['class' => 'form-control']) !!}
            </div>
        </div>
        </legend>
    </fieldset>
</div>
<div style="margin-top: 20px" class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-map-o"></i> Croquis</legend>
        <div class="form-group" style="text-align: center">         
            <div class="col-sm-12" style="text-align: center">
                <input id="croquis" name="Croquis" type="file" class="file-loading">
            </div>
        </div>
    </fieldset>
</div>    
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    <a class="btn btn-info" href="{{ URL::previous() }}">Regresar</a>        
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
</div>
{!! Form::close() !!}
@stop