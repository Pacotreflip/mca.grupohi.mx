@extends('layout')

@section('content')
<h1>{{ $marca->Descripcion }}
    <a href="{{ route('marcas.edit', $marca) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit') }}</a>
    <a href="{{ route('marcas.destroy', $marca) }}" class="btn btn-danger pull-right marcas_destroy" style="margin-right: 5px"><i class="fa fa-close"></i> {{ trans('strings.delete') }}</a>
</h1>
{!! Breadcrumbs::render('marcas.show', $marca) !!}
<hr>
{!! Form::model($marca) !!}
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <div class="form-group">
        {!! Form::label('Descripcion', 'Descripción', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Descripcion', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('Estatus', 'Estatus', ['class' => 'control-label col-sm-3']) !!}
        <div class="col-sm-9">
            {!! Form::text('Estatus', $marca->present()->estatus, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
</div>
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('marcas.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
{!! Form::close() !!}
@stop