@extends('layout')

@section('content')
<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.carga_manual_completa') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-manual-completa inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <form>
            <div class="col-xs-6 col-md-3 form-group">
                <label class="col-xs-12">No. de Viajes</label>
                <div class="col-xs-9 col-md-10">
                    <input type="number" class="form-control" v-model="numViajes">
                </div>
                <button type="submit" class="btn btn-sm btn-success col-xs-3 col-md-2" v-on:click="fillTable">
                    <span v-if="cargando"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else><i class="fa fa-play-circle"></i></span>
                </button>
            </div>
            </form>
            <div class="table-responsive col-md-12 col-xs-12 rcorners" v-if="!cargando">
                <table class="table table-bordered table-hover">                    
                    <thead>
                        <tr>
                            <th rowspan="2">ID</th>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Fecha De Llegada</th>
                            <th rowspan="2">
                                Camión
                                <br>
                                <select v-if="form.viajes.length" class="form-control input-sm" v-model="generales.IdCamion" v-on:change="setCamionGeneral">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="camion in camiones" v-bind:value="camion.IdCamion">@{{camion.Economico}}</option>
                                </select>
                            </th>
                            <th rowspan="2">Cubicación</th>
                            <th rowspan="2">
                                Origen
                                <br>
                                <select v-if="form.viajes.length" class="form-control input-sm" v-model="generales.IdOrigen" v-on:change="setOrigenGeneral">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="origen in origenes" v-bind:value="origen.IdOrigen">@{{origen.Descripcion}}</option>
                                </select>
                            </th>
                            <th rowspan="2">
                                Tiro
                                <br>
                                <select v-if="form.viajes.length" class="form-control input-sm" v-model="generales.IdTiro" v-on:change="setTiroGeneral">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="tiro in tiros" v-bind:value="tiro.IdTiro">@{{tiro.Descripcion}}</option>
                                </select>
                            </th>
                            <th rowspan="2">
                                Ruta
                            </th>
                            <th rowspan="2">
                                Material
                                <br>
                                <select v-if="form.viajes.length" class="form-control input-sm" v-model="generales.IdMaterial" v-on:change="setMaterialGeneral">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="material in materiales" v-bind:value="material.IdMaterial">@{{material.Descripcion}}</option>
                                </select>
                            </th>
                            <th colspan="3">Tarifas</th>
                            <th rowspan="2">Turno</th>
                            <th rowspan="2">Observaciones</th>
                        </tr>
                        <tr>
                            <th>1er. Km</th>
                            <th>Km. Subs.</th>
                            <th>Km. Adic.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="viaje in form.viajes">
                            <td>@{{viaje.Id}}</td>
                            <td>
                                <input class="form-control input-sm mediano" type="number" v-model="viaje.NumViajes">
                            </td>
                            <td>
                                <input class="form-control input-sm grande" id="FechaLlegada" @blur="setFechaLlegada(viaje, $event)" v-datepicker type="text" class="fecha" v-model="viaje.FechaLlegada">
                            </td>
                            <td>
                                <select class="form-control input-sm grande" v-model="viaje.IdCamion" v-on:change="setCubicacion(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="camion in camiones" v-bind:value="camion.IdCamion">@{{camion.Economico}}</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm mediano" type="number" step="any" v-model="viaje.Cubicacion">
                            </td>
                            <td>
                                <select class="form-control input-sm grande" v-model="viaje.IdOrigen" v-on:change="fetchRutas(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="origen in origenes" v-bind:value="origen.IdOrigen">@{{origen.Descripcion}}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm grande" v-model="viaje.IdTiro" v-on:change="fetchRutas(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="tiro in tiros" v-bind:value="tiro.IdTiro">@{{tiro.Descripcion}}</option>
                                </select>
                            </td>
                            <td style="width: 100px">
                                <select class="form-control input-sm mediano" v-model="viaje.IdRuta">
                                    <option v-if="!viaje.Rutas.length" value>---</option>
                                    <option v-else v-for="ruta in viaje.Rutas" v-bind:value="ruta.IdRuta">@{{ruta.Clave + ruta.IdRuta}}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm grande" v-model="viaje.IdMaterial" v-on:change="fetchKms(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="material in materiales" v-bind:value="material.IdMaterial">@{{material.Descripcion}}</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm mediano" type="number" step="any" v-model="viaje.PrimerKm">
                            </td>
                            <td>
                                <input class="form-control input-sm mediano" type="number" step="any" v-model="viaje.KmSub">
                            </td>
                            <td>
                                <input class="form-control input-sm mediano" type="number" step="any" v-model="viaje.KmAd">
                            </td>
                            <td>
                                <label for="m">M:</label>
                                <input type="radio" value="M" v-model="viaje.Turno">
                                <br>
                                <label for="v">V:</label>
                                <input type="radio" value="V" v-model="viaje.Turno">  
                            </td>
                            <td>
                                <textarea class="form-control input-sm" style="width: 300px" rows="3" type="text" v-model="viaje.Observaciones"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-show="form.viajes.length" class="form-group col-md-12 col-xs-12" style="text-align: center; margin-top: 20px">
                <button class="btn btn-success" v-on:click="confirmarCarga">Guardar</button>
            </div>
        </section>
    </viajes-manual-completa>
</div>
@stop