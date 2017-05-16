@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.new_ruta')) }}</h1>
{!! Breadcrumbs::render('rutas.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'rutas.store', 'files' => true, 'id' => 'create_ruta']) !!}
<div class="row rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('IdOrigen', 'Origen', ['class' => 'control-label']) !!}
                {!! Form::select('IdOrigen', $origenes, null, ['placeholder' => '--SELECCIONE--', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('IdTiro', 'Tiro', ['class' => 'control-label']) !!}
                {!! Form::select('IdTiro', $tiros, null, ['placeholder' => '--SELECCIONE--', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('IdTipoRuta','Tipo de Ruta', ['class' => 'control-label'])!!}
                {!! Form::select('IdTipoRuta', $tipos, null, ['placeholder' => '--SELECCIONE--', 'class' => 'form-control']) !!}
            </div>
        </div>
    </fieldset>
</div>
<br><br>
<div class="row rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-tachometer"></i> Kilometraje</legend>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('PrimerKm', 'Primer KM', ['class' => 'control-label']) !!}
                {!! Form::number('PrimerKm', 1, ['class' => 'form-control km', 'readonly' => 'readonly']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('KmSubsecuentes', 'KM Subsecuentes', ['class' => 'control-label']) !!}
                {!! Form::text('KmSubsecuentes', '0', ['class' => 'form-control km']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('KmAdicionales', 'KM Adicionales', ['class' => 'control-label']) !!}
                {!! Form::text('KmAdicionales', '0', ['class' => 'form-control km']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('TotalKM', 'KM Total', ['class' => 'control-label']) !!}
                {!! Form::number('TotalKM', null, ['class' => 'form-control totalKm', 'readonly' => 'readonly']) !!}
            </div>
        </div>
    </fieldset>    
</div>
<br><br>
<div class="row rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-clock-o"></i> Cronometría</legend>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('TiempoMinimo', 'Tiempo Mínimo (min)', ['class' => 'control-label']) !!}
                {!! Form::text('TiempoMinimo', '0', ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('TiempoMinimo', 'Tiempo Mínimo (min)', ['class' => 'control-label']) !!}
                {!! Form::text('TiempoMinimo', '0', ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Tolerancia', 'Tolerancia (min)', ['class' => 'control-label']) !!}
                {!! Form::text('Tolerancia', '0', ['class' => 'form-control']) !!}
            </div>
        </div>
        </legend>
    </fieldset>
</div>
<br><br>
<div class="row rcorners">
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