@extends('layout')

@section('content')
<h1>{{ $ruta->present()->clave }}
    <a href="{{ route('rutas.edit', $ruta) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit_ruta') }}</a>
    <a href="{{ route('rutas.destroy', $ruta) }}" class="btn btn-danger pull-right rutas_destroy" style="margin-right: 5px"><i class="fa fa-close"></i> {{ trans('strings.delete_ruta') }}</a>
</h1>
{!! Breadcrumbs::render('rutas.show', $ruta) !!}
<hr>
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('IdOrigen', 'Origen', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('IdOrigen', $ruta->origen->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            {!! Form::label('IdTiro', 'Tiro', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::text('IdTiro', $ruta->tiro->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('IdTipoRuta','Tipo de Ruta', ['class' => 'control-label col-sm-2'])  !!}
            <div class="col-sm-4">
                {!! Form::text('IdTipoRuta', $ruta->tipoRuta->Descripcion, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>     
            {!! Form::label('Croquis', 'Archivo de Croquis', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                <a class="btn btn-info col-md-12" href="{{ URL::to('/').'/'.$ruta->archivo->Ruta }}">
                    <i class="fa fa-file-{{$ruta->archivo->Tipo == 'application/pdf' ? 'pdf' : 'image'}}-o"></i> VER ARCHIVO                  
                </a>  
            </div>
        </div>
    </fieldset>
</div>

<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    {!! link_to_route('rutas.index', 'Regresar', [],  ['class' => 'btn btn-info'])!!}
</div>
@stop