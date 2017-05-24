@extends('layout')

@section('content')
    <h1>ADMINISTRACIÓN DE ROLES Y PERMISOS</h1>
    <div id="app">
        <global-errors></global-errors>
        <roles-permisos inline-template>
            <section>
                <app-errors v-bind:form="form"></app-errors>


                <div class="row">
                    {{dd(role('administrador-sistema'))}}
                    @role('administrador-sistema')
                    <div class="panel panel-default">
                        <div class="panel-heading">Módulo de Creación de Permisos y Roles
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-6">

                                <div class="panel panel-default">
                                    <div class="panel-heading">Creación de Permisos
                                    </div>
                                    <div class="panel-body">
                                        <form id="permisos_store_form" class="form-horizontal" action="{{ route('permisos.store') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="shortP">Nombre Corto:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="shortP" name="name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="nombreP">Nombre:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="nombreP" name="display_name">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="descripcionP">Descripción:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="descripcionP" name="description">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-4 col-sm-10">
                                                    <button :disabled="guardando" type="submit" class="btn btn-default" v-on:click="permisos_store">
                                                        <span v-if="guardando">
                                                    <i class="fa fa-spinner fa-spin"></i> Registrando
                                                </span>
                                                        <span v-else>
                                                    <i class="fa fa-save"></i> Registrar
                                                </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Creación de Roles
                                    </div>
                                    <div class="panel-body">
                                        <form  id="roles_store_form"  class="form-horizontal" action="{{route('roles.store')}}" method="POST" >
                                            {{csrf_field()}}
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="shortR">Nombre Corto:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="shortR" name="name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="nombreR">Nombre:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="nombreR" name="display_name">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="descripcionR">Descripción:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="descripcionR" name="description">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-4 col-sm-10">
                                                    <button :disabled="guardando" type="submit" class="btn btn-default" v-on:click="roles_store">
                                                        <span v-if="guardando">
                                                    <i class="fa fa-spinner fa-spin"></i> Registrando
                                                </span>
                                                        <span v-else>
                                                    <i class="fa fa-save"></i> Registrar
                                                </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endrole
                    <div class="panel panel-default">
                        <div class="panel-heading">Configuración de Roles y Perfiles
                        </div>
                        <div class="panel-body">
                            @role('administrador-sistema')
                            <div class="col-sm-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Asignación de permisos al rol
                                    </div>
                                    <div class="row">
                                        <div class="panel-body">
                                            <form class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-4" for="nombreR">Seleccione Rol:</label>
                                                    <div class="col-sm-7">
                                                        <select class="input form-control" v-on:change="select_rol" v-model="selected_rol_id" id="rol">
                                                            <option value="">[--Seleccione--]</option>
                                                            <option v-for="rol in roles" v-bind:value="rol.id">@{{ rol.display_name }}</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                    <div v-show="selected_rol_id != ''" class="panel-body">

                                        <div  class="row">

                                            <div  class="col-sm-5">
                                                <center><b>Disponibles</b></center>
                                                <select id="leftRolValues" size="20" class="form-control"   multiple>

                                                </select>
                                            </div>

                                            <div class="col-sm-2">
                                                <button type="button" :disabled="guardando"  id="btnRolRight" value="&gt;&gt;"
                                                        class="btn btn-default center-block add"  v-on:click="add_rol_click" >
                                                    <span v-if="guardando">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </span>
                                                    <span v-else>
                                                    <i class="fa"></i> &gt;&gt;
                                                </span>
                                                </button>
                                                <button type="button" :disabled="guardando" id="btnRolLeft" value="&lt;&lt;"
                                                        class="btn btn-default center-block add" v-on:click="add_rol_click" >
                                                    <span v-if="guardando">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </span>
                                                    <span v-else>
                                                    <i class="fa"></i> &lt;&lt;
                                                </span>
                                                </button>
                                            </div>
                                            <div class="col-sm-5">
                                                <center><b>Actuales</b></center>
                                                <select id="rightRolValues" size="20" class="form-control" multiple>

                                                </select>
                                            </div>
                                        </div>
                                        <center>
                                            <button :disabled="guardando" v-on:click="guardar_permisos_rol" class="btn btn-success">
                                                <span v-if="guardando">
                                                    <i class="fa fa-spinner fa-spin"></i> Guardando
                                                </span>
                                                <span v-else>
                                                    <i class="fa fa-save"></i> Guardar
                                                </span>
                                            </button>
                                        </center>
                                    </div>
                                </div>


                            </div>
                            @endrole
                            <div class="col-sm-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Asignación de roles a usuario
                                    </div>
                                    <div class="row">
                                        <div class="panel-body">
                                            <form class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-4" for="nombreUs">Seleccione Usuario:</label>
                                                    <div class="col-sm-7">
                                                        <select id="selUser" class="input form-control" v-on:change="select_usuario" v-model="selected_usuario_id" >
                                                            <option value="">[--Seleccione--]</option>
                                                            <option v-for="usuario in usuarios" v-bind:value="usuario.id">@{{ usuario.nombre }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                    <div class="panel-body" v-show="selected_usuario_id != ''">

                                        <div class="row">

                                            <div  class="col-sm-5">
                                                <center><b>Disponibles</b></center>
                                                <select id="leftPermisoValues" size="20" class="form-control"   multiple>
                                                    </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" :disabled="guardando"  id="btnPermisoRight" value="&gt;&gt;"
                                                        class="btn btn-default center-block add"  v-on:click="add_permiso_click" >
                                                      <span v-if="guardando">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </span>
                                                    <span v-else>
                                                    <i class="fa"></i> &gt;&gt;
                                                </span>
                                                </button>

                                                <button type="button" :disabled="guardando" id="btnPermisoLeft" value="&lt;&lt;"
                                                        class="btn btn-default center-block add" v-on:click="remove_permiso_click">
                                                      <span v-if="guardando">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </span>
                                                    <span v-else>
                                                    <i class="fa "></i> &lt;&lt;
                                                </span>
                                                </button>
                                            </div>
                                            <div class="col-sm-5">
                                                <center><b>Actuales</b></center>
                                                <select id="rightPermisoValues" size="20" class="form-control" multiple>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>


                <form  id="permiso_rol_form"  class="form-horizontal" action="{{route('permisos_roles.store')}}" method="POST" >
                {{csrf_field()}}
                </form>
                <form  id="rol_usuario_form"  class="form-horizontal" action="{{route('rol_usuario.store')}}" method="POST" >
                    {{csrf_field()}}
                </form>
            </section>
        </roles-permisos>
    </div>





@stop
