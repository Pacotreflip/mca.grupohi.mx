@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.edit_sindicato')) }}</h1>
{!! Breadcrumbs::render('sindicatos.edit', $sindicato) !!}
<hr>
@include('partials.errors')

{!! Form::model($sindicato, ['method' => 'PATCH', 'route' => ['sindicatos.update', $sindicato]]) !!}

<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Descripcion', 'Descripción', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Descripcion', $sindicato->Descripcion, ['class' => 'form-control', 'placeholder' => 'Descripción...']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('NombreCorto', 'Nombre Corto', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('NombreCorto', $sindicato->NombreCorto, ['class' => 'form-control', 'placeholder' => 'Nombre Corto...']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'Estatus', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::select('Estatus', ['1' => 'ACTIVO', '0' => 'INACTIVO'], null, ['placeholder' => 'Seleccione un Estatus...', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group " style="text-align: center">
        {!! Form::submit('Actualizar', ['class' => 'btn btn-primary']) !!}
    </div>
</div>

{!! Form::close() !!}
@stop