@extends('layout')

@section('content')
    <h1>MATERIALES</h1>
    {!! Breadcrumbs::render('materiales.show', $material) !!}
    <hr>
    <div class="row"></div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Descripción</label>
                <input type="text" class="form-control" value="{{$material->Descripcion}}" disabled>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Registró</label>
                <input type="text" class="form-control" value="{{$material->user_registro->present()->nombreCompleto}}" disabled>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Fecha y Hora de Registro</label>
                <input type="text" class="form-control" value="{{$material->created_at->format('Y-M-d H:i:s a')}}" disabled>
            </div>
        </div>
    </div>
@endsection