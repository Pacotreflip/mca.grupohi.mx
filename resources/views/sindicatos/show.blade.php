@extends('layout')

@section('content')
<h1>{{ $sindicato->Descripcion }}
    <a href="{{ route('sindicatos.edit', $sindicato) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit') }}</a>
    <a href="{{ route('sindicatos.destroy', $sindicato) }}" class="btn btn-danger pull-right sindicatos_destroy" style="margin-right: 5px"><i class="fa fa-close"></i> {{ trans('strings.delete') }}</a>
</h1>
{!! Breadcrumbs::render('sindicatos.show', $sindicato) !!}
<hr>
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Descripcion', 'Descripción', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Descripcion', $sindicato->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('NombreCorto', 'Nombre Corto', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('NombreCorto', $sindicato->NombreCorto, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'Estatus', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Estatus', $sindicato->present()->estatus, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group " style="text-align: center">
        {!! link_to_route('sindicatos.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
    </div>
</div>
@stop