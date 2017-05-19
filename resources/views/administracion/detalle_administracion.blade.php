@extends('layout')
@section('content')
    <style>
        .table {
            font-size: 9px;
        }
    </style>

    <h1>DETALLE DE ADMINISTRACIÓN</h1>
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#usuario_permiso">Usuario Rol</a></li>
            <li><a data-toggle="tab" href="#permiso_rol">Rol Permiso</a></li>
            <li><a data-toggle="tab" href="#rol_usuario">Rol Usuario</a></li>
        </ul>

        <div class="tab-content">
            <div id="usuario_permiso" class="tab-pane fade in active">
                <div class="container"  style="overflow-x: scroll;">
                    <a href="{{ route('csv.rol_permiso') }}" style="margin-right: 5px"
                       class="btn btn-default pull-right"><i class="fa fa-file-excel-o"></i> EXCEL</a>

                    <table class="table table-striped table-bordered small">
                        <thead>
                        <tr>
                            <th style="width: 300px" class="th">Usuario</th>
                            @foreach($roles as $rol)
                                <th style="width: 3090px" class="Rotar"><span
                                            class="Rotar">{{$rol->display_name}}</span></th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles_usuarios as $rolUser)
                            <tr>
                                <td> {{$rolUser['usuario']['nombre']}}</td>
                                @foreach($rolUser['roles'] as $rol)
                                    <th  @if($rol==1)style="background-color: #dff0d8"@endif>@if($rol==1)<center><span class="fa fa-check"></span></center>@endif</th>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <div id="permiso_rol" class="tab-pane fade">
                <div class="row">
                <a href="{{ route('csv.usuario_rol') }}" style="margin-right: 5px" class="btn btn-default pull-right"><i
                            class="fa fa-file-excel-o"></i> EXCEL</a>
                </div>

                <div class="container" style="overflow-x: scroll;">

                    <table class="table table-striped table-bordered small">
                        <thead>
                        <tr>
                            <th style="width: 300px" class="th">Rol</th>
                            @foreach($permisos as $permiso)
                                <th style="width: 3090px" class="Rotar"><span
                                            class="Rotar">{{$permiso->display_name}}</span></th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permisos_roles as $permisoRol)
                            <tr>


                                <td> {{$permisoRol['rol']->display_name}}</td>
                                @foreach($permisoRol['permisosActuales'] as $rol)

                                    <th  @if($rol==1)style="background-color: #dff0d8"@endif>@if($rol==1)<center><span class="fa fa-check"></span></center>@endif</th>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <div id="rol_usuario" class="tab-pane fade">
                <h3>Usuario Permisos</h3>

                <div class="row">
                    <a href="{{ route('csv.usuario_rol') }}" style="margin-right: 5px" class="btn btn-default pull-right"><i
                                class="fa fa-file-excel-o"></i> EXCEL</a>
                </div>

                <div class="container" style="overflow-x: scroll;">

                    <table class="table table-striped table-bordered small">
                        <thead>
                        <tr>
                            <th style="width: 300px" class="th">Rol</th>
                            @foreach($permisos as $permiso)
                                <th style="width: 3090px" class="Rotar"><span
                                            class="Rotar">{{$permiso->display_name}}</span></th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>


                        @foreach($permisos_usuario as $permisoUsuario)
                            <tr>


                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@stop
