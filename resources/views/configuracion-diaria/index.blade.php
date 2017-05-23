@extends('layout')

@section('content')
    <h1>CONFIGURACIÓN DIARIA
        <a href="{{ route('csv.configuracion-checadores') }}" class="btn btn-success pull-right"><i class="fa fa-file-excel-o"></i> Descargar Excel </a>
        <a href="{{ route('pdf.configuracion-diaria')}}"   target="_blank" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-pdf-o"></i> Descargar PDF</a>
    </h1>
    {!! Breadcrumbs::render('configuracion-diaria.index') !!}
    <hr>

    <div id="app">
        <global-errors></global-errors>
        <configuracion-diaria inline-template rol_checador="{{ $rol->id }}">
            <section>
                <div v-if="cargando" class="row">
                    <h4 style="text-align: center"><i class="fa fa-spinner fa-spin fa-lg"></i> CARGANDO </h4>
                </div>
                <div v-else class="row">
                    <!-- Configuración de Checadores-->
                    <div v-if="checadores.length" class="col-md-12" >
                        <div class="table-responsive">
                            <table class="table small">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="text-align: center">Nombre</th>
                                    <th style="text-align: center">Usuario Intranet</th>
                                    <th style="text-align: center">Teléfono</th>
                                    <th style="text-align: center">Origen / Tiro</th>
                                    <th style="text-align: center">Ubicación </th>
                                    <th style="text-align: center">Perfil</th>
                                    <th style="text-align: center">Turno</th>
                                    <th style="text-align: center">Guardar</th>
                                    <th style="text-align: center">Limpiar Configuración</th>
                                    <th style="text-align: center">Quitar Permiso de Checador</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(user, index) in checadores">
                                    <td style="white-space: nowrap">@{{ index + 1 }}</td>
                                    <td style="white-space: nowrap">@{{ user.nombre  }}</td>
                                    <td style="white-space: nowrap">@{{ user.usuario }}</td>
                                    <td>
                                        <span v-if="user.telefono">
                                            <a title="Cambiar" style="text-decoration: underline" v-on:click="asignar_telefono(user)">@{{ user.telefono.info  }}</a>
                                        </span>
                                        <span v-else>
                                            <a title="Asignar" style="text-decoration: underline" v-on:click="asignar_telefono(user)">ASIGNAR</a>
                                        </span>
                                    </td>
                                    <td style="white-space: nowrap">
                                        <select v-on:change="clear_ubicacion(user)" name="tipo" class="form-control input-sm" v-model="user.configuracion.tipo">
                                            <option value>-- SELECCIONE --</option>
                                            <option value="0">Origen</option>
                                            <option value="1">Tiro</option>
                                        </select>
                                    </td>
                                    <td style="white-space: nowrap">
                                        <select v-if="user.configuracion.tipo == 0" v-on:change="set_ubicacion(user, $event)" name="ubicacion" class="form-control input-sm" v-bind:disabled="user.configuracion.tipo == ''">
                                            <option v-bind:selected="user.configuracion.ubicacion.id == ''" value>-- SELECCIONE --</option>
                                            <option v-bind:selected="user.configuracion.ubicacion.id == origen.id"  v-for="origen in origenes" v-bind:value="origen.id">@{{ origen.descripcion }}</option>
                                        </select>
                                        <select v-else-if="user.configuracion.tipo == 1" v-on:change="set_ubicacion(user, $event)" name="ubicacion" class="form-control input-sm" v-bind:disabled="user.configuracion.tipo == ''">
                                            <option v-bind:selected="user.configuracion.ubicacion.id == ''" value>-- SELECCIONE --</option>
                                            <option v-bind:selected="user.configuracion.ubicacion.id == tiro.id"  v-for="tiro in tiros" v-bind:value="tiro.id">@{{ tiro.descripcion }}</option>
                                        </select>
                                    </td>
                                    <td style="white-space: nowrap" v-if="user.configuracion.ubicacion.id != ''">
                                         <select v-if="user.configuracion.tipo == 0" name="perfil" class="form-control input-sm" v-model="user.configuracion.id_perfil" >
                                             <option value>-- SELECCIONE --</option>
                                            <option  v-for="perfil in para_origen" v-bind:value="perfil.id">@{{ perfil.name }}</option>
                                        </select> 
                                        <select v-if="user.configuracion.tipo == 1" name="perfil" class="form-control input-sm" v-model="user.configuracion.id_perfil" >
                                             <option value>-- SELECCIONE --</option>
                                            <option  v-for="perfil in para_tiro" v-bind:value="perfil.id">@{{ perfil.name }}</option>
                                        </select>
                                    </td>
                                    <td style="white-space: nowrap" v-else>
                                       <select class="form-control input-sm" disabled="disabled">
                                           <option value>-- SELECCIONE --</option>
                                       </select>
                                    </td>
                                    <td style="white-space: nowrap">
                                        <select name="turno" class="form-control input-sm" v-model="user.configuracion.turno" :disabled="!user.configuracion.id_perfil">
                                            <option value>-- SELECCIONE --</option>
                                            <option value="M">Matutino</option>
                                            <option value="V">Vespertino</option>
                                        </select>
                                    </td>
                                    <td style="text-align: center; white-space: nowrap">
                                        <button @click="guardar_configuracion(user)" type="submit" class="btn btn-xs btn-success" :disabled="user.guardando">
                                            <i v-if="user.guardando" class="fa fa-spinner fa-spin fa-lg"></i>
                                            <i v-else class="fa fa-save fa-lg"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center; white-space: nowrap">
                                        <button @click="quitar_configuracion(user)" type="button" class="btn btn-xs btn-warning" :disabled=" ! user.configuracion.id || user.guardando">
                                            <i v-if="user.guardando" class="fa fa-spinner fa-spin fa-lg"></i>
                                            <i v-else class="fa fa-undo fa-lg"></i>
                                        </button>
                                    </td>
                                    <td style="text-align: center; white-space: nowrap">
                                        <button @click="confirmar_quitar_checador(user)" type="button" class="btn btn-xs btn-danger" :disabled="user.guardando">
                                            <i v-if="user.guardando" class="fa fa-spinner fa-spin fa-lg"></i>
                                            <i v-else class="fa fa-remove fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Modal Configuración del Teléfono -->
                <div class="modal fade" id="telefonos_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">
                                    <span v-if="current_checador && current_checador.telefono">
                                        CAMBIAR TELÉFONO
                                    </span>
                                    <span v-else>
                                        ASIGNAR TELÉFONO
                                    </span>
                                </h4>
                            </div>
                            {!! Form::open(['id' => 'asignar_telefono_form']) !!}
                            <div class="modal-body">
                                <app-errors v-bind:form="form"></app-errors>
                                <span v-if="current_checador">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>CHECADOR</label>
                                                <p>@{{ current_checador.nombre }}</p>
                                                <input type="hidden" name="id_checador" v-bind:value="current_checador.id">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>TELÉFONO</label>
                                                <span v-if="current_checador.telefono">
                                                    <select name="id_telefono" class="form-control">
                                                        <option value>-- SELECCIONE --</option>
                                                        <option selected v-bind:value="current_checador.telefono.id">@{{ current_checador.telefono.info }}</option>
                                                        <option v-for="telefono in telefonos" v-bind:value="telefono.id">@{{ telefono.info  }}</option>
                                                    </select>
                                                </span>
                                                <span v-else>
                                                    <select name="id_telefono" class="form-control">
                                                        <option selected value>-- SELECCIONE --</option>
                                                        <option v-for="telefono in telefonos" v-bind:value="telefono.id">@{{ telefono.info }}</option>
                                                    </select>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" v-on:click="cancelar_asignacion">Cerrar</button>
                                <button type="submit" class="btn btn-success" v-on:click="confirmar_asignacion">Asignar</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </section>
        </configuracion-diaria>
    </div>
@endsection