@extends('layout')
@section('content')
    <h1>TELÉFONOS
        <a href="{{route('impresoras.edit', $impresora)}}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> Editar</a>
    </h1>
    {!! Breadcrumbs::render('impresoras.show', $impresora) !!}
    <hr>
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">INFORMACIÓN DE LA IMPRESORA</div>

        <!-- List group -->
        <ul class="list-group">
            <li class="list-group-item"><strong>ID:</strong> {{$impresora->id}}</li>
            <li class="list-group-item"><strong>MAC:</strong> {{$impresora->mac}}</li>
            <li class="list-group-item"><strong>MARCA:</strong> {{$impresora->marca}}</li>
            <li class="list-group-item"><strong>MODELO:</strong> {{$impresora->modelo}}</li>
             <li class="list-group-item"><strong>FECHA Y HORA REGISTRO:</strong> {{$impresora->created_at->format('d-M-Y h:i:s a')}} ({{$impresora->created_at->diffForHumans()}})</li>
            <li class="list-group-item"><strong>PERSONA QUE REGITRÓ:</strong> {{$impresora->user_registro->present()->nombreCompleto()}}</li>

        </ul>
    </div>
@endsection