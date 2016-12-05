@extends('layout')

@section('content')
<div class='success'></div>
<h2>{{ strtoupper(trans('strings.tarifas_peso')) }}</h2>
{!! Breadcrumbs::render('tarifas_peso.index') !!}
<hr>
<div class="errores"></div>
<div class="table-responsive col-md-6 col-md-offset-3">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Material</th>
                <th>Tarifa 1er. KM</th>
                <th>Tarifa KM Subsecuentes</th>
                <th>Tarifa KM Adicionales</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materiales as $material)
            @if($material->tarifaPeso()->count())
            {!! Form::model($material->tarifaPeso, ['class' => 'tarifa_create', 'method' => 'PATCH', 'route' => ['tarifas_peso.update', $material->tarifaPeso]]) !!}
            @else
            {!! Form::open(['class' => 'tarifa_create', 'route' => 'tarifas_peso.store']) !!}
            @endif
            {!! Form::hidden('IdMaterial', $material->IdMaterial) !!}
            <tr>
                <td>{{ $material->Descripcion }}</td>
                <td>{!! Form::number('PrimerKM', null, ['step' => 'any', 'class' => 'form-control'])!!}</td>
                <td>{!! Form::number('KMSubsecuente', null, ['step' => 'any', 'class' => 'form-control']) !!}</td>
                <td>{!! Form::number('KMAdicional', null, ['step' => 'any', 'class' => 'form-control']) !!}</td>
                <td>{!! Form::submit('GUARDAR', ['class' => 'btn btn-success form-control'])!!}</td>
            </tr>
            {!! Form::close() !!}
            @endforeach
        </tbody>
    </table>
</div>
@stop