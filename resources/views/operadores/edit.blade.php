@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.edit')) }}</h1>
{!! Breadcrumbs::render('operadores.edit', $operador) !!}
<hr>
@include('partials.errors')

{!! Form::model($operador, ['method' => 'PATCH', 'route' => ['operadores.update', $operador]]) !!}

<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Nombre', 'Nombre', ['class' => 'control-label col-sm-2']) !!}
        <div class="col-sm-10">
            {!! Form::text('Nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre...']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Direccion', 'Dirección', ['class' => 'control-label col-sm-2']) !!}
        <div class="col-sm-10">
            {!! Form::text('Direccion', null, ['class' => 'form-control', 'placeholder' => 'Dirección...']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('NoLicencia', 'Número de Licencia', ['class' => 'control-label col-sm-2']) !!}
        <div class="col-sm-4">
            {!! Form::text('NoLicencia', null, ['class' => 'form-control', 'placeholder' => 'Número de Licencia...']) !!}
        </div>
        {!! Form::label('VigenciaLicencia', 'Vigencia de Licencia', ['class' => 'control-label col-sm-2']) !!}
        <div class="col-sm-4">
            {!! Form::text('VigenciaLicencia', null, ['class' => 'form-control vigencia', 'placeholder' => 'Vigencia de Licencia...']) !!}
        </div>
    </div>
</div>
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    <a class="btn btn-info" href="{{ URL::previous() }}">Regresar</a>        
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
</div>
{!! Form::close() !!}
@stop