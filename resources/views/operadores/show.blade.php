@extends('layout')

@section('content')
<h1>{{ $operador->Nombre }}
    <a href="{{ route('operadores.edit', $operador) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit') }}</a>
    <a href="{{ route('operadores.destroy', $operador) }}" class="btn btn-danger pull-right operadores_destroy" style="margin-right: 5px"><i class="fa fa-close"></i> {{ trans('strings.delete') }}</a>
</h1>
{!! Breadcrumbs::render('operadores.show', $operador) !!}
<hr>
{!! Form::model($operador) !!}
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Nombre', 'Nombre', ['class' => 'control-label col-sm-2']) !!}
        <div class="col-sm-10">
            {!! Form::text('Nombre', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Direccion', 'Dirección', ['class' => 'control-label col-sm-2']) !!}
        <div class="col-sm-10">
            {!! Form::text('Direccion', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('NoLicencia', 'Número de Licencia', ['class' => 'control-label col-sm-2']) !!}
        <div class="col-sm-4">
            {!! Form::text('NoLicencia', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
        {!! Form::label('VigenciaLicencia', 'Vigencia de Licencia', ['class' => 'control-label col-sm-2']) !!}
        <div class="col-sm-4">
            {!! Form::text('VigenciaLicencia', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
</div>
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('operadores.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
{!! Form::close() !!}
@stop