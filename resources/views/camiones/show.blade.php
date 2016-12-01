@extends('layout')

@section('content')
<h1>{{ $camion->present()->datos }}
    <a href="{{ route('camiones.edit', $camion) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit') }}</a>
    <a href="{{ route('camiones.destroy', $camion) }}" class="btn pull-right camiones_destroy {{ $camion->Estatus == 1 ? 'activo btn-danger' : 'inactivo btn-success' }}" style="margin-right: 5px"><i class="fa {{ $camion->Estatus == 1 ? 'fa-close' : 'fa-plus' }}"></i> {{ $camion->Estatus == 1 ? trans('strings.delete') : trans('strings.activate') }}</a>
</h1>
{!! Breadcrumbs::render('camiones.show', $camion) !!}
<hr>
{!! Form::model($camion) !!}
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('IdSindicato', 'Sindicato', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('IdSindicato', $camion->sindicato->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('Propietario', 'Propietario', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('Propietario', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('IdOperador','Operador', ['class' => 'control-label col-sm-2'])  !!}
            <div class="col-sm-4">
                {!! Form::text('IdOperador',$camion->operador->Nombre, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>     
            {!! Form::label('Economico', 'Económico', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('Economico', null, ['class' => 'form-control' , 'disabled' => 'disabled']) !!}  
            </div>
        </div>
    </fieldset>
</div>
{!! Form::close() !!}
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('camiones.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
@stop