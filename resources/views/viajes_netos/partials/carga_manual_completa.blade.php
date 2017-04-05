@extends('layout')

@section('content')
<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes_netos.carga_manual_completa') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-manual-completa inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <div class="form-inline">
                <div class="form-group">
                    <label>Número de Viajes </label>
                    <input type="number" class="form-control input-sm" v-model="numViajes">
                </div>
                <button class="btn btn-sm btn-primary" v-on:click="fillTable">
                    <span v-if="cargando"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else><i class="fa fa-play-circle"></i></span>
                </button>
            </div>
            <hr>
            <div class="table-responsive col-md-12 col-xs-12" v-if="!cargando">
                <table v-if="form.viajes.length" class="table table-condensed table-hover table-bordered">                    
                    <thead>
                        <tr>
                            <th rowspan="2">ID</th>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Fecha De Llegada</th>
                            <th rowspan="2">
                                Camión
                                <br>
                                <select class="form-control input-sm" v-model="generales.IdCamion" v-on:change="setCamionGeneral($event)">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($camiones as $camion)
                                    <option value="{{ $camion->IdCamion}}">{{ $camion->Economico }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th rowspan="2">Cubicación</th>
                            <th rowspan="2">
                                Origen
                                <br>
                                <select class="form-control input-sm" v-model="generales.IdOrigen" v-on:change="setOrigenGeneral($event)">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($origenes as $origen)
                                    <option value="{{ $origen->IdOrigen }}">{{ $origen->Descripcion }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th rowspan="2">
                                Tiro
                                <br>
                                <select class="form-control input-sm" v-model="generales.IdTiro" v-on:change="setTiroGeneral($event)">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($tiros as $tiro)
                                    <option value="{{ $tiro->IdTiro}}">{{ $tiro->Descripcion }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th rowspan="2">
                                Ruta
                            </th>
                            <th rowspan="2">
                                Material
                                <br>
                                <select class="form-control input-sm" v-model="generales.IdMaterial" v-on:change="setMaterialGeneral($event)">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($materiales as $material)
                                    <option value="{{ $material->IdMaterial}}">{{ $material->Descripcion }}</option>
                                    @endforeach
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
                                <select class="form-control input-sm grande" v-model="viaje.IdCamion" v-on:change="setCubicacion(viaje, $event)">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($camiones as $camion)
                                    <option value="{{ $camion->IdCamion}}">{{ $camion->Economico }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input class="form-control input-sm mediano" type="number" step="any" v-model="viaje.Cubicacion">
                            </td>
                            <td>
                                <select class="form-control input-sm grande" v-model="viaje.IdOrigen" v-on:change="fetchRutas(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($origenes as $origen)
                                    <option value="{{ $origen->IdOrigen}}">{{ $origen->Descripcion }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm grande" v-model="viaje.IdTiro" v-on:change="fetchRutas(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($tiros as $tiro)
                                    <option value="{{ $tiro->IdTiro }}">{{ $tiro->Descripcion }}</option>
                                    @endforeach
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
                                    @foreach($materiales as $material)
                                    <option value="{{ $material->IdMaterial}}">{{ $material->Descripcion }}</option>
                                    @endforeach
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