@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.tarifas_material')) }}</h1>
{!! Breadcrumbs::render('tarifas_material.index') !!}
<hr>
@include('partials.errors')
<div class="table-responsive col-md-8 col-md-offset-2">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Material</th>
                <th>Tarifa 1er. KM</th>
                <th>Tarifa KM Subsecuentes</th>
                <th>Tarifa KM Adicionales</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materiales as $material)
            @if($material->tarifaMaterial()->count())
            {!! Form::model($material->tarifaMaterial, ['method' => 'PATCH', 'route' => ['tarifas_material.update', $material->tarifaMaterial]]) !!}
            @else
            {!! Form::open(['route' => 'tarifas_material.store']) !!}
            @endif
            {!! Form::hidden('IdMaterial', $material->IdMaterial) !!}
            <tr>
                <td>{{ $material->Descripcion }}</td>
                <td>{!! Form::number('PrimerKM', null, ['step' => 'any', 'class' => 'form-control'])!!}</td>
                <td>{!! Form::number('KMSubsecuente', null, ['step' => 'any', 'class' => 'form-control']) !!}</td>
                <td>{!! Form::number('KMAdicional', null, ['step' => 'any', 'class' => 'form-control']) !!}</td>
                @if($material->tarifaMaterial()->count())
                <td>{{ $material->tarifaMaterial->present()->estatus }}</td>
                @else
                <td></td>
                @endif
                <td>{!! Form::submit('GUARDAR', ['class' => 'btn btn-success form-control'])!!}</td>
            </tr>
            {!! Form::close() !!}
            @endforeach
        </tbody>
    </table>
</div>
@stop
