@extends('layout')

@section('content')
<h1>{{ $material->Descripcion }}
    <a href="{{ route('materiales.edit', $material) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit') }}</a>
    <a href="{{ route('materiales.destroy', $material) }}" class="btn pull-right element_destroy {{ $material->Estatus == 1 ? 'activo btn-danger' : 'inactivo btn-success' }}" style="margin-right: 5px"><i class="fa {{ $material->Estatus == 1 ? 'fa-ban' : 'fa-check' }}"></i> {{ $material->Estatus == 1 ? 'INHABILITAR' : 'HABILITAR' }}</a>
</h1>
{!! Breadcrumbs::render('materiales.show', $material) !!}
<hr>
{!! Form::model($material) !!}
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
            {!! Form::text('Estatus', $material->present()->estatus, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    </div>
</div>
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('materiales.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
{!! Form::close() !!}
@stop