@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.new_empresa')) }}</h1>
{!! Breadcrumbs::render('empresas.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'empresas.store']) !!}

<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('razonSocial', 'Razón Social', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('razonSocial', null, ['class' => 'form-control', 'placeholder' => 'Razón Social...']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('RFC', 'RFC', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('RFC', null, ['class' => 'form-control', 'placeholder' => 'RFC...']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'Estatus', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::select('Estatus', ['1' => 'ACTIVO', '0' => 'INACTIVO'], '1', ['placeholder' => '--SELECCIONE--', 'class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    <a class="btn btn-info" href="{{ URL::previous() }}">Regresar</a>        
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
</div>
{!! Form::close() !!}
@stop