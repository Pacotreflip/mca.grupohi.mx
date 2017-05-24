@extends('layout')

@section('content')
    <h1>USUARIO SISTEMA

        <a href="{{ route('usuarios_sistema.create') }}" class="btn btn-success pull-right" ><i class="fa fa-plus"></i> NUEVO USUARIO </a>
        <a href="{{ route('csv.telefonos')}}" style="margin-right: 5px" class="btn btn-default pull-right"><i class="fa fa-file-excel-o"></i> EXCEL</a>
    </h1>
    {!! Breadcrumbs::render('telefonos.index') !!}
    <hr>
    <div class="table-responsive">
        <table class="table table-hover table-striped small">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ap. Paterno</th>
                <th>Ap. Materno</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Estatus</th>
                <th style="width: 100px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->idusuario }}</td>
                    <td>{{ $usuario->apaterno }}</td>
                    <td>{{ $usuario->amaterno }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->usuario }}</td>
                    <td>{{ $usuario->usuario_estado}}</td>

                    <td>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Form eliminar Teléfono -->
    <form id="eliminar_telefono" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="delete">
        <input type="hidden" name="motivo" value/>
    </form>
@endsection