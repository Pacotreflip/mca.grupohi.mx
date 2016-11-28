@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.new_origin')) }}</h1>
{!! Breadcrumbs::render('origenes.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'origenes.store']) !!}

<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Tipo', 'Tipo de Origen', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::select('IdTipoOrigen', $tipos, null, ['placeholder' => 'Seleccione un Tipo...', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Descripcion', 'Descripción', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Descripcion', null, ['class' => 'form-control', 'placeholder' => 'Descripción...']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'Estatus', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::select('Estatus', ['1' => 'ACTIVO', '0' => 'INACTIVO'], '1', ['placeholder' => 'Seleccione un Estatus...', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group " style="text-align: center">        
        <a class="btn btn-info" href="{{ URL::previous() }}">Regresar</a>        
        {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
    </div>
</div>

{!! Form::close() !!}
@stop