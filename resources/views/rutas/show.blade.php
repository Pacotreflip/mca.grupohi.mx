@extends('layout')

@section('content')
<h1>{{ $ruta->present()->clave }}
    <a href="{{ route('rutas.edit', $ruta) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit_ruta') }}</a>
    <a href="{{ route('rutas.destroy', $ruta) }}" class="btn btn-danger pull-right rutas_destroy" style="margin-right: 5px"><i class="fa fa-close"></i> {{ trans('strings.delete_ruta') }}</a>
</h1>
{!! Breadcrumbs::render('rutas.show', $ruta) !!}
<hr>
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('IdOrigen', 'Origen', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('IdOrigen', $ruta->origen->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('IdTiro', 'Tiro', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('IdTiro', $ruta->tiro->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('IdTipoRuta','Tipo de Ruta', ['class' => 'control-label col-sm-2'])  !!}
            <div class="col-sm-4">
                {!! Form::text('IdTipoRuta', $ruta->tipoRuta->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
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
            {!! Form::label('Primer', 'Primer KM', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('Primer', $ruta->PrimerKm, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Subsecuentes', 'KM Subsecuentes', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('Subsecuentes', $ruta->KmSubsecuentes, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Adicionales', 'KM Adicionales', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('Adicionales', $ruta->KmAdicionales, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Total', 'KM Total', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('Total', $ruta->TotalKM, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
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
                {!! Form::number('TiempoMinimo', $ruta->cronometria->TiempoMinimo, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Tolerancia', 'Tolerancia (min)', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::number('Tolerancia', $ruta->cronometria->Tolerancia, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        </legend>
    </fieldset>
</div>
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('rutas.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
@stop