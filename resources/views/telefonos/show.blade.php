@extends('layout')
@section('content')
    <h1>TELÉFONOS
        @permission('editar-telefonos')
        <a href="{{ route('telefonos.edit', $telefono) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> Editar</a>
        @endpermission
    </h1>
    {!! Breadcrumbs::render('telefonos.show', $telefono) !!}
    <hr>
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">INFORMACIÓN DEL TELÉFONO</div>

        <!-- List group -->
        <ul class="list-group">
            <li class="list-group-item"><strong>ID:</strong> {{$telefono->id}}</li>
            <li class="list-group-item"><strong>IMEI:</strong> {{$telefono->imei}}</li>
            <li class="list-group-item"><strong>LINEA TELEFÓNICA:</strong> {{$telefono->linea}}</li>
            <li class="list-group-item"><strong>MARCA:</strong> {{$telefono->marca}}</li>
            <li class="list-group-item"><strong>MODELO:</strong> {{$telefono->modelo}}</li>
            <li class="list-group-item"><strong>ASIGNADO A CHECADOR:</strong> {{$telefono->checador}}</li>

            <li class="list-group-item"><strong>FECHA Y HORA REGISTRO:</strong> {{$telefono->created_at->format('d-M-Y h:i:s a')}} ({{$telefono->created_at->diffForHumans()}})</li>
            <li class="list-group-item"><strong>PERSONA QUE REGITRÓ:</strong> {{$telefono->user_registro->present()->nombreCompleto()}}</li>
        </ul>
    </div>
@endsection