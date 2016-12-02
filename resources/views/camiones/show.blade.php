@extends('layout')

@section('content')
<h1>{{ $camion->present()->datos }}
    <a href="{{ route('camiones.edit', $camion) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit') }}</a>
    <a href="{{ route('camiones.destroy', $camion) }}" class="btn pull-right camiones_destroy {{ $camion->Estatus == 1 ? 'activo btn-danger' : 'inactivo btn-success' }}" style="margin-right: 5px"><i class="fa {{ $camion->Estatus == 1 ? 'fa-close' : 'fa-plus' }}"></i> {{ $camion->Estatus == 1 ? trans('strings.delete') : trans('strings.activate') }}</a>
</h1>
{!! Breadcrumbs::render('camiones.show', $camion) !!}
<hr>
{!! Form::model($camion) !!}
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('Sindicato', 'Sindicato', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Sindicato', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Propietario', 'Propietario', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Propietario', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Operador','Operador', ['class' => 'control-label col-sm-1'])  !!}
            <div class="col-sm-3">
                {!! Form::text('Operador', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Economico','Económico', ['class' => 'control-label col-sm-1'])  !!}
            <div class="col-sm-3">
                {!! Form::text('Economico',null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>     
            {!! Form::label('Placas', 'Placas Camión', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Placas', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
            {!! Form::label('PlacasCaja', 'Placas Caja', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('PlacasCaja', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Marca', 'Marca', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Marca', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
            {!! Form::label('Modelo', 'Modelo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Modelo', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
            {!! Form::label('Boton', 'Dispositivo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Boton', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
        </div>
    </fieldset>
</div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners" style="margin-top: 20px"> 
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Fotográfica</legend>
        <div class="form-group">
            @if($camion->imagenes)
            @foreach($camion->imagenes as $imagen)
            {!! Form::label($imagen->TipoC, $imagen->TipoC, ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                <a class="btn btn-info col-md-12"  href="{{ URL::to('/').'/'.$camion->archivo->Ruta }}" target="blank">
                    <i class="fa fa-file-image-o"></i> VER FOTO                  
                </a> 
            </div>
            @endforeach
            @else
            <h2 style="text-align: center">No hay imagenes...</h2>
            @endif
        </div>
    </fieldset>
</div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners" style="margin-top: 20px">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-lock"></i> Información de Seguro</legend>
        <div class="form-group">
            {!! Form::label('Aseguradora', 'Aseguradora', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('Aseguradora', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('PolizaSeguro', 'Poliza', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('PolizaSeguro', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('VigenciaPolizaSeguro', 'Vigencia', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('VigenciaPolizaSeguro', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
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
                {!! Form::text('Ancho', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Largo', 'Largo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('Largo', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Alto', 'Alto', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('Alto', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('EspacioDeGato', 'Gato', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('EspacioDeGato', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('AlturaExtension', 'Extensión', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('AlturaExtension', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('CubicacionReal', 'Cubicación Real', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('CubicacionReal', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('CubicacionParaPago', 'Cubicación para Pago', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('CubicacionParaPago', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
    </fieldset>
</div>
{!! Form::close() !!}
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('camiones.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
@stop