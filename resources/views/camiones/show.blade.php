@extends('layout')

@section('content')
<h1>{{ $camion->present()->datosCamion }}
    <a href="{{ route('camiones.edit', $camion) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> EDITAR</a>
    <a href="{{ route('camiones.destroy', $camion) }}" class="btn pull-right element_destroy {{ $camion->Estatus == 1 ? 'activo btn-danger' : 'inactivo btn-success' }}" style="margin-right: 5px"><i class="fa {{ $camion->Estatus == 1 ? 'fa-ban' : 'fa-check' }}"></i> {{ $camion->Estatus == 1 ? 'INHABILITAR' : 'HABILITAR' }}</a>
</h1>
{!! Breadcrumbs::render('camiones.show', $camion) !!}
<hr>
{!! Form::model($camion) !!}
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('Sindicato', 'Sindicato', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                {!! Form::text('Sindicato', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Empresa', 'Empresa', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                    {!! Form::text('Empresa', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Propietario', 'Propietario', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                {!! Form::text('Propietario', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Operador','Operador', ['class' => 'control-label col-sm-1'])  !!}
            <div class="col-sm-5">
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
            <div class="col-sm-2">
                {!! Form::text('Marca', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
            {!! Form::label('Modelo', 'Modelo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('Modelo', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
            {!! Form::label('Boton', 'Dispositivo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('Boton', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
            {!! Form::label('Imagenes', 'Imagenes', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
            @if($camion->imagenes->count() > 0)
            <a class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-lg">Ver imagenes</a>
            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            @foreach($camion->imagenes as $key => $imagen)
                            <div class="item {{$key == 0 ? 'active' : ''}}">
                                <img class="img-responsive" src="data:{{$imagen->Tipo}};base64,{{$imagen->Imagen}}" alt="{{$imagen->present()->tipoImagen}}">
                                <div class="carousel-caption"><h1>{{$imagen->present()->tipoImagen}}</h1></div>
                            </div>
                            @endforeach
                        </div>
                            <!-- Controls -->
                            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                              <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                              <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
           @else 
           <button class="btn btn-default" disabled="disabled">Sin imagenes</button>
           @endif
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
            {!! Form::label('Disminucion', 'Disminución', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::text('Disminucion', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
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