@extends('layout')

@section('content')
<h1>REGISTRO MANUAL DE VIAJES</h1>
{!! Breadcrumbs::render('viajesnetos.create') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-registro-manual inline-template>
        <section>            <app-errors v-bind:form="form"></app-errors>

            <div v-if="!cargando" class="table-responsive col-md-10 col-md-offset-1 rcorners">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Camión</th>
                            <th>Origen</th>
                            <th>Tiro</th>
                            <th>Material</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input id="FechaLlegada" @blur="setFechaLlegada($event)" v-datepicker type="text" class="form-control fecha input-sm" v-model="form.FechaLlegada">
                            </td>
                            <td>
                                <input type="time" class="form-control input-sm" v-model="form.HoraLlegada">
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="form.IdCamion">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="camion in camiones" v-bind:value="camion.IdCamion">@{{ camion.Economico }}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="form.IdOrigen" v-on:change="fetchTiros">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="origen in origenes" v-bind:value="origen.IdOrigen">@{{ origen.Descripcion }}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="form.IdTiro">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="tiro in tiros" v-bind:value="tiro.IdTiro">@{{ tiro.Descripcion }}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="form.IdMaterial">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="material in materiales" v-bind:value="material.IdMaterial">@{{ material.Descripcion }}</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Observaciones<strong></td>
                            <td colspan="5">
                                <input type="text" class="form-control input-sm" v-model="form.Observaciones">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
                <button class="btn btn-success btn-sm" type="submit" @click="guardar">
                    <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i> Guardar</span>
                    <span v-else><i class="fa fa-save"></i> Guardar</span>
                </button>
            </div>
        </section>
    </viajes-registro-manual>
</div>
@stop