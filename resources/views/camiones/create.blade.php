@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.new_camion')) }}</h1>
{!! Breadcrumbs::render('camiones.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'camiones.store', 'files' => true]) !!}
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('IdSindicato', 'Sindicato', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('IdSindicato', $sindicatos, null,  ['class' => 'form-control', 'placeholder' => 'Seleccione un Sindicato...']) !!}
            </div>
            {!! Form::label('Propietario', 'Propietario', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Propietario', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('IdOperador','Operador', ['class' => 'control-label col-sm-1'])  !!}
            <div class="col-sm-3">
                {!! Form::select('IdOperador', $operadores, null,  ['class' => 'form-control', 'placeholder' => 'Seleccione un Operador...']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Economico','Económico', ['class' => 'control-label col-sm-1'])  !!}
            <div class="col-sm-3">
                {!! Form::text('Economico',null, ['class' => 'form-control']) !!}
            </div>     
            {!! Form::label('Placas', 'Placas Camión', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Placas', null, ['class' => 'form-control']) !!}  
            </div>
            {!! Form::label('PlacasCaja', 'Placas Caja', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('PlacasCaja', null, ['class' => 'form-control']) !!}  
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('IdMarca', 'Marca', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('IdMarca', $marcas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Marca...']) !!}  
            </div>
            {!! Form::label('Modelo', 'Modelo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Modelo', null, ['class' => 'form-control']) !!}  
            </div>
            {!! Form::label('IdBoton', 'Dispositivo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('IdBoton', $botones, null, ['class' => 'form-control' , 'placeholder' => 'Seleccione un Dispositivo...']) !!}  
            </div>
        </div>
    </fieldset>
</div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners" style="margin-top: 20px"> 
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Fotográfica</legend>
        <div class="form-group">
            <div class="col-sm-3">
            </div>
        </div>
    </fieldset>
</div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners" style="margin-top: 20px">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-lock"></i> Información de Seguro</legend>
        <div class="form-group">
            {!! Form::label('Aseguradora', 'Aseguradora', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('Aseguradora', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('PolizaSeguro', 'Poliza', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('PolizaSeguro', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('VigenciaPolizaSeguro', 'Vigencia', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('VigenciaPolizaSeguro', null, ['class' => 'form-control vigencia']) !!}
            </div>
        </div>
    </fieldset>
</div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners" style="margin-top: 20px">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-arrows"></i> Información de Cubicación</legend>
        <div class="form-group">
            {!! Form::label('Ancho', 'Ancho', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('Ancho', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('Largo', 'Largo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('Largo', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('Alto', 'Alto', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('Alto', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('EspacioDeGato', 'Gato', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('EspacioDeGato', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('AlturaExtension', 'Extensión', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('AlturaExtension', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('CubicacionReal', 'Cubicación Real', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('CubicacionReal', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('CubicacionParaPago', 'Cubicación para Pago', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('CubicacionParaPago', null, ['class' => 'form-control']) !!}
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
@section('scripts')
<script>
    $('.vigencia').datepicker({
    format: 'yyyy-mm-dd',
    language: 'es',
    autoclose: true,
    clearBtn: true,
    todayHighlight: true
    });
</script>
@stop