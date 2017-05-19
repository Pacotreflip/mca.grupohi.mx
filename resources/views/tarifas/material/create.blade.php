@extends('layout')

@section('content')
<div class='success'></div>
<h1>{{ strtoupper(trans('strings.tarifas_material')) }} </h1>
{!! Breadcrumbs::render('tarifas_material.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'tarifas_material.store']) !!}
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="IdMaterial">MATERIAL</label>
            {!! Form::select('IdMaterial', $materiales, null, ['placeholder' => '--SELECCIONE--', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <label for="InicioVigencia">INICIO VIGENCIA</label>
        {!! Form::date('InicioVigencia', date("Y-m-d"), ['class' => 'form-control', 'placeholder' => '0']) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label for="PrimerKM">TARIFA PRIMER KM</label>
        {!! Form::text('PrimerKM', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
    </div>
    <div class="col-md-4">
        <label for="KMSubsecuente">TARIFA KM SUBSECUENTES</label>
        {!! Form::text('KMSubsecuente', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
    </div>
    <div class="col-md-4">
        <label for="KMAdicional">TARIFA KM ADICIONALES</label>
        {!! Form::text('KMAdicional', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
    </div>
</div>
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
</div>
{!! Form::close() !!}
@stop