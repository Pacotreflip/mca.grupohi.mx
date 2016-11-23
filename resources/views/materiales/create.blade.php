@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.new_material')) }}</h1>
{!! Breadcrumbs::render('materiales.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'materiales.store']) !!}

<div class="col-md-6 col-md-offset-3" style="text-align: center">
    <div class="form-group">
        {!! Form::label('Descripcion', 'DESCRIPCIÓN', ['class' => 'control-label']) !!}
        {!! Form::text('Descripcion', null, ['class' => 'form-control', 'placeholder' => 'Descripción...']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'ESTATUS', ['class' => 'control-label']) !!}
        {!! Form::select('Estatus', ['1' => 'ACTIVO', '0' => 'INACTIVO'], null, ['placeholder' => 'Seleccione un Estatus...', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    </div>
</div>

{!! Form::close() !!}
@stop