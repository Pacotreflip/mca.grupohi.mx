@extends('layout')

@section('content')
<div class='success'></div>
<h1>{{ strtoupper(trans('strings.tarifas_material')) }} </h1>
{!! Breadcrumbs::render('tarifas_material.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'tarifas_material.store']) !!}

<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    
    <div class="form-group">
        <div class="row">
            <div class="col-md-8" style="text-align: center">
                Material
            </div>
            <div class="col-md-4" style="text-align: center">
                Inicio Vigencia
            </div>
        </div>
        <div class="row">
            <div class="col-md-8" style="text-align: center">
                {!! Form::select('IdMaterial', $materiales, null, ['placeholder' => '--SELECCIONE--', 'class' => 'form-control']) !!}
            </div>
            <div class="col-md-4" style="text-align: center">
                {!! Form::date('InicioVigencia', date("Y-m-d"), ['class' => 'form-control', 'placeholder' => '0']) !!}
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4" style="text-align: center">
                Tarifa Primer KM
            </div>
            <div class="col-md-4" style="text-align: center">
                Tarifa KM Subsecuentes
            </div>
            <div class="col-md-4" style="text-align: center">
                Tarifa KM Adicionales
            </div>
                
        </div>
        <div class="row">
            <div class="col-md-4">
                {!! Form::number('PrimerKM', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::number('KMSubsecuente', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::number('KMAdicional', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    <a class="btn btn-info" href="{{ URL::previous() }}">Regresar</a>        
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
</div>
{!! Form::close() !!}
@stop