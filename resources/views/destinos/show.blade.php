@extends('layout')

@section('content')
<h1>{{ $destino->Descripcion }}
    <a href="{{ route('destinos.edit', $destino) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit_destino') }}</a>
    <a href="{{ route('destinos.destroy', $destino) }}" class="btn btn-danger pull-right destinos_destroy" style="margin-right: 5px"><i class="fa fa-close"></i> {{ trans('strings.delete_destino') }}</a>
</h1>
{!! Breadcrumbs::render('destinos.show', $destino) !!}
<hr>
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Clave', 'Clave', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Clave', $destino->present()->clave, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Descripcion', 'Descripción', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Descripcion', $destino->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'Estatus', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Estatus', $destino->present()->estatus, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group " style="text-align: center">
        {!! link_to_route('destinos.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
    </div>
</div>
@stop