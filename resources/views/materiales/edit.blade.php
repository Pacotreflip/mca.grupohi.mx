@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.new_material')) }}</h1>
{!! Breadcrumbs::render('materiales.edit', $material) !!}
<hr>
@include('partials.errors')

{!! Form::model($material, [
    'method' => 'PATCH',
    'route' => ['materiales.update', $material]
]) !!}
<div class="col-md-6 col-md-offset-3" style="text-align: center">
    <div class="form-group">
        {!! Form::label('Descripcion', 'DESCRIPCIÓN', ['class' => 'control-label']) !!}
        {!! Form::text('Descripcion', $material->Descripcion, ['class' => 'form-control', 'placeholder' => 'Descripción...']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'ESTATUS', ['class' => 'control-label']) !!}
        {!! Form::select('Estatus', ['1' => 'ACTIVO', '0' => 'INACTIVO'], null, ['placeholder' => 'Seleccione un Estatus...', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Actualizar', ['class' => 'btn btn-primary']) !!}
    </div>
</div>

{!! Form::close() !!}
@stop