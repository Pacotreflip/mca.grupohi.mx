@extends('layout')

@section('content')
    <h1>CONFIGURACIÓN DIARIA</h1>
    {!! Breadcrumbs::render('configuracion-diaria.index') !!}
    <hr>

    <div id="app">
        <global-errors></global-errors>
        <configuracion-diaria inline-template>
            <section>
                <div v-if="cargando" class="row">
                    <h4 style="text-align: center"><i class="fa fa-spinner fa-spin fa-lg"></i> CARGANDO </h4>
                </div>
                <div v-else class="row">
                    <!-- Configuración de Tiros -->
                    <div class="col-md-4">
                        <h3>CONFIGURACIÓN DE TIROS</h3>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-condensed small">
                                <thead>
                                <tr>
                                    <th style="text-align: center">#</th>
                                    <th style="text-align: center">Tiros</th>
                                    <th style="text-align: center">Esquema</th>
                                    <th style="text-align: center">Guardar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(tiro, index) in tiros">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ tiro.descripcion }}</td>
                                    <td>
                                        <select class="form-control input-sm" v-model="tiro.esquema.id">
                                            <option value>-- SELECCIONE --</option>
                                            <option v-for="esquema in esquemas" v-bind:value="esquema.id">@{{ esquema.name }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-xs btn-success" @click="cambiar_esquema(tiro, $event)" v-bind:disabled="guardando">
                                            <i v-if="tiro.guardando" class="fa fa-spinner fa-spin fa-lg"></i>
                                            <i v-else class="fa fa-save fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Configuración de Checadores-->
                    <div v-if="checadores.length" class="col-md-7 col-md-offset-1">
                        <h3>CONFIGURACIÓN DE CHECADORES</h3>
                        <p><small><sup>1</sup> Se enlistarán todos los origenes y tiros, excepto aquellos tiros que no tengan un esquema definido.</small></p>
                        <hr>
                        <div class="table-responsive">
                            <table class="table small">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="text-align: center">Nombre</th>
                                    <th style="text-align: center">Usuario Intranet</th>
                                    <th style="text-align: center">Origen / Tiro</th>
                                    <th style="text-align: center">Ubicación <sup>1</sup></th>
                                    <th style="text-align: center">Perfil</th>
                                    <th style="text-align: center">Guardar</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(user, index) in checadores">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ user.nombre  }}</td>
                                    <td>@{{ user.usuario }}</td>
                                    <td>
                                        <select v-on:change="clear_ubicacion(user)" name="tipo" class="form-control input-sm" v-model="user.configuracion.tipo">
                                            <option value>-- SELECCIONE --</option>
                                            <option value="O">Origen</option>
                                            <option value="T">Tiro</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select v-on:change="set_ubicacion(user, $event)" name="ubicacion" class="form-control input-sm" v-bind:disabled="user.configuracion.tipo == ''">
                                            <option v-bind:selected="user.configuracion.ubicacion.id == ''" value>-- SELECCIONE --</option>
                                            <option v-bind:selected="user.configuracion.ubicacion.id == tiro.id" v-if="user.configuracion.tipo == 'T'" v-for="tiro in con_esquema" v-bind:value="tiro.id">@{{ tiro.descripcion }}</option>
                                            <option v-bind:selected="user.configuracion.ubicacion.id == origen.id" V-else-if="user.configuracion.tipo == 'O'" v-for="origen in origenes" v-bind:value="origen.id">@{{ origen.descripcion }}</option>
                                        </select>
                                    </td>
                                    <td v-if="user.configuracion.ubicacion.id != ''">
                                         <select class="form-control" name="perfil" v-model="user.configuracion.id_perfil">
                                             <option value>-- SELECCIONE --</option>
                                             <option v-for="perfil in user.configuracion.ubicacion.esquema.perfiles" v-bind:value="perfil.id">@{{ perfil.name }}</option>
                                         </select>
                                    </td>
                                    <td v-else>
                                       <select class="form-control" disabled="disabled">
                                           <option value>-- SELECCIONE --</option>
                                       </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-success" v-bind:disabled="guardando">
                                            <i v-if="guardando" class="fa fa-spinner fa-spin fa-lg"></i>
                                            <i v-else class="fa fa-save fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </configuracion-diaria>
    </div>
@endsection