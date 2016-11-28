@extends('layout')

@section('content')
<h1>{{ $tiro->Descripcion }}
    <a href="{{ route('tiros.edit', $tiro) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit_tiro') }}</a>
    <a href="{{ route('tiros.destroy', $tiro) }}" class="btn btn-danger pull-right tiros_destroy" style="margin-right: 5px"><i class="fa fa-close"></i> {{ trans('strings.delete_tiro') }}</a>
</h1>
{!! Breadcrumbs::render('tiros.show', $tiro) !!}
<hr>
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Clave', 'Clave', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Clave', $tiro->present()->clave, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Descripcion', 'Descripción', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Descripcion', $tiro->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'Estatus', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Estatus', $tiro->present()->estatus, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group " style="text-align: center">
        {!! link_to_route('tiros.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
    </div>
</div>
@stop