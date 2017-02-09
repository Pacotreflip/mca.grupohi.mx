<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.validar') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-validar inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <div class="row">
                <div class="col-md-4 col-xs-6">
                    <div class="form-inline">
                        <div class="form-group">
                            <label>Inicio</label>
                            <input type="text" v-model="datosConsulta.fechaInicial" v-datepicker class="fecha form-control input-sm" placeholder="Fecha de Inicio" @blur="setFechaInicial($event)">
                        </div>
                        <div class="form-group">
                            <label>Fin</label>
                            <input type="text" v-model="datosConsulta.fechaFinal" v-datepicker class="fecha form-control input-sm" placeholder="Fecha de Fin" @blur="setFechaFinal($event)">
                        </div>
                        <button v-on:click="fetchViajes" class="btn btn-sm btn-primary" >Consultar</button>
                    </div>
                </div>
                <div class="text-right col-md-4 col-xs-6 col-md-offset-4">
                    <div class="form-inline">
                        <div class="form-group">
                            <label>Buscar código</label>
                            <input type="text" v-model="datosConsulta.code" class="form-control input-sm" placeholder="Código del viaje">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <br>
            <div class="table-responsive">
                <span v-if="cargando">
                    <div class="text-center">
                        <i class="fa fa-2x fa-spinner fa-spin"></i> Cargando Viajes...
                    </div>
                </span>
                <table id="viajes_netos_validar" v-tablefilter v-if="viajes.length" class="table table-condensed table-bordered table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2" rowspan="2">Fecha de Llegada</th>
                            <th rowspan="2">Hora de Llegada</th>
                            <th rowspan="2">?</th>
                            <th rowspan="2">Tiro</th>
                            <th rowspan="2">Camión</th>
                            <th rowspan="2">Origen</th>
                            <th rowspan="2">Material</th>
                            <th rowspan="2">Tiempo</th>
                            <th rowspan="2">Ruta</th>
                            <th rowspan="2">Distancia</th>
                            <th colspan="3">Tarifa</th>
                            <th rowspan="2">Importe</th>
                        </tr>
                        <tr>
                            <th>1er Km</th>
                            <th>Km Sub.</th>
                            <th>Km Adc.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="viaje in getViajesByCode">
                            <td>@{{ viaje.FechaLlegada }}</td>
                            <td>@{{ viaje.HoraLlegada }}</td>
                            <td>
                                <span v-if="viaje.Valido">
                                    <a id="show-modal" @click="viaje.ShowModal = true">
                                        <i class="fa fa-flag" style="color: green"></i> Validar     
                                    </a>
                                    <modal-validar v-if="viaje.ShowModal" @close="viaje.ShowModal = false">
                                        <h3 slot="header">Validar Viaje</h3>
                                        <div slot="body" class="form-horizontal">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label>Sindicato:</label>
                                                        <select v-model="viaje.Sindicato" class="form-control input-sm">
                                                            <option v-for="sindicato in sindicatos" v-bind:value="sindicato.IdSindicato">@{{ sindicato.NombreCorto }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Empresa:</label>
                                                        <select v-model="viaje.Empresa" class="form-control input-sm">
                                                            <option v-for="empresa in empresas" v-bind:value="empresa.IdEmpresa">@{{ empresa.razonSocial }}</option>
                                                        </select>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group">
                                                        <label>Tipo Tarifa:</label>
                                                        <select v-model="viaje.TipoTarifa" class="form-control input-sm">
                                                            <option value="m">Material</option>
                                                            <option value="r">Ruta</option>
                                                            <option value="p">Peso</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tipo FDA:</label>
                                                        <select v-model="viaje.TipoFDA" class="form-control input-sm">
                                                            <option value="m">Material</option>
                                                            <option value="bm">Ban-Mat</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-md-offset-1">
                                                    <div class="form-group">
                                                        <label>Cubicación:</label>
                                                        <input type="number" step="any" class="form-control input-sm" v-model="viaje.Cubicacion">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tara:</label>
                                                        <input type="number" step="any" class="form-control input-sm" v-model="viaje.Tara">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Bruto:</label>
                                                        <input type="number" step="any" class="form-control input-sm" v-model="viaje.Bruto">
                                                    </div>
                                                    <hr>
                                                    <span v-if="viaje.Valido">
                                                        <div >
                                                            <label><i class="fa fa-check" style="color: green"></i> Validar:</label>
                                                            <input checked="checked" v-switchbox v-bind:id="viaje.IdViajeNeto" type="checkbox" value="1" v-bind:name="checkboxName(viaje.IdViajeNeto)"/>
                                                        </div>
                                                        <div>
                                                            <label><i class="fa fa-close" style="color: red"></i> Denegar:</label>
                                                            <input v-switchbox v-bind:id="viaje.IdViajeNeto" type="checkbox" value="0" v-bind:name="checkboxName(viaje.IdViajeNeto)"/>
                                                        </div>   
                                                    </span>
                                                    <span v-else>
                                                        <div >
                                                            <label><i class="fa fa-check" style="color: green"></i> Validar:</label>
                                                            <input disabled="disabled" v-bind:id="viaje.IdViajeNeto" type="checkbox" value="1" v-bind:name="checkboxName(viaje.IdViajeNeto)"/>
                                                        </div>
                                                        <div>
                                                            <label><i class="fa fa-close" style="color: red"></i> Denegar:</label>
                                                            <input checked="checked" v-bind:id="viaje.IdViajeNeto" type="checkbox" value="0" v-bind:name="checkboxName(viaje.IdViajeNeto)"/>
                                                        </div>   
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" slot="footer">
                                            <button class="btn btn-info btn-sm" @click="viaje.ShowModal = false">Cerrar</button>        
                                            <button class="btn btn-success btn-sm" @click="confirmarValidacion(viaje)">
                                                Validar
                                            </button>
                                        </div>
                                    </modal-validar>
                                </span>
                                <span v-else>
                                    <a v-bind:title="viaje.Estado"><i class="fa fa-flag" style="color: red"></i></a>
                                </span>
                            </td>
                            <td>@{{ viaje.Tiro }}</td>
                            <td>@{{ viaje.Camion }}</td>
                            <td>@{{ viaje.Origen }}</td>
                            <td>@{{ viaje.Material }}</td>
                            <td>@{{ viaje.Tiempo }}</td>
                            <td>@{{ viaje.Ruta }}</td>
                            <td>@{{ viaje.Distancia }}</td>
                            <td>@{{ viaje.PrimerKM }}</td>
                            <td>@{{ viaje.KMSubsecuente }}</td>
                            <td>@{{ viaje.KMAdicional }}</td>
                            <td>@{{ viaje.Importe }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </viajes-validar>
</div>