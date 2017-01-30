@extends('layout')

@section('content')
<h1>{{ $sindicato->Descripcion }}
    <a href="{{ route('sindicatos.edit', $sindicato) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit') }}</a>
    <a href="{{ route('sindicatos.destroy', $sindicato) }}" class="btn pull-right element_destroy {{ $sindicato->Estatus == 1 ? 'activo btn-danger' : 'inactivo btn-success' }}" style="margin-right: 5px"><i class="fa {{ $sindicato->Estatus == 1 ? 'fa-ban' : 'fa-check' }}"></i> {{ $sindicato->Estatus == 1 ? 'INHABILITAR' : 'HABILITAR' }}</a>
</h1>
{!! Breadcrumbs::render('sindicatos.show', $sindicato) !!}
<hr>
{!! Form::model($sindicato) !!}
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Descripcion', 'Descripción', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Descripcion', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('NombreCorto', 'Nombre Corto', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('NombreCorto', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('sindicatos.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
@stop