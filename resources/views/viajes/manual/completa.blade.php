@extends('layout')

@section('content')
<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.manual.completa') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-manual-completa inline-template>
        <section>
            <div class="col-xs-6 col-md-3 form-group">
                <label class="col-xs-12">No. de Viajes</label>
                <div class="col-xs-9 col-md-10">
                    <input type="number" class="form-control" v-model="numViajes">
                </div>
                <button class="btn btn-sm btn-success col-xs-3 col-md-2" v-on:click="fillTable"><i class="fa fa-play-circle"></i></button>
            </div>   
            <div class="table-reponsive rcorners col-md-12">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Fecha De Llegada</th>
                            <th rowspan="2">
                                Camión
                                <br>
                                <select class="form-control input-sm" v-model="generales.IdCamion">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="camion in camiones" v-bind:value="camion.IdCamion">@{{camion.Economico}}</option>
                                </select>
                            </th>
                            <th rowspan="2">Cubicación</th>
                            <th rowspan="2">
                                Origen
                                <br>
                                <select class="form-control input-sm" v-model="generales.IdOrigen" v-on:change="fetchRutas">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="origen in origenes" v-bind:value="origen.IdOrigen">@{{origen.Descripcion}}</option>
                                </select>
                            </th>
                            <th rowspan="2">
                                Tiro
                                <br>
                                <select class="form-control input-sm" v-model="generales.IdTiro" v-on:change="fetchRutas">
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
                                <select class="form-control input-sm" v-model="generales.IdMaterial">
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
                                <input id="FechaLlegada" @blur="setFechaLlegada(viaje, $event)" v-datepicker type="text" class="form-control fecha input-sm" v-model="viaje.FechaLlegada">
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdCamion" v-on:change="setCubicacion(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="camion in camiones" v-bind:value="camion.IdCamion">@{{camion.Economico}}</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm" type="number" step="any" v-model="viaje.Cubicacion">
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdOrigen" v-on:change="fetchRutas(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="origen in origenes" v-bind:value="origen.IdOrigen">@{{origen.Descripcion}}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdTiro" v-on:change="fetchRutas(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="tiro in tiros" v-bind:value="tiro.IdTiro">@{{tiro.Descripcion}}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdRuta">
                                    <option value>---</option>
                                    <option v-for="ruta in viaje.Rutas">@{{ruta.Clave + ruta.IdRuta}}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdMaterial">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="material in materiales" v-bind:value="material.IdMaterial">@{{material.Descripcion}}</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm" type="number" step="any" v-model="viaje.PrimerKm">
                            </td>
                            <td>
                                <input class="form-control input-sm" type="number" step="any" v-model="viaje.KmSub">
                            </td>
                            <td>
                                <input class="form-control input-sm" type="number" step="any" v-model="viaje.KmAd">
                            </td>
                            <td>
                                <label for="m">M:</label>
                                <input type="radio" value="m" v-model="viaje.Turno">
                                <br>
                                <label for="v">V:</label>
                                <input type="radio" value="v" v-model="viaje.Turno">  
                            </td>
                            <td>
                                <input class="form-control input-sm" type="text" v-model="viaje.Observaciones"> 
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </viajes-manual-completa>
</div>
@stop