@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.new_material')) }}</h1>
{!! Breadcrumbs::render('materiales.create') !!}
<hr>
@include('partials.errors')

{!! Form::open(['route' => 'materiales.store']) !!}

<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" name="Descripcion" class="form-control">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Persona que Registra</label>
            <input type="text" class="form-control" value="{{auth()->user()}}" disabled>
        </div>
    </div>
</div>
<div class="form-group">
    {!! Form::submit('Guardar', ['class'    => 'btn btn-success']) !!}
</div>
{!! Form::close() !!}
@stop