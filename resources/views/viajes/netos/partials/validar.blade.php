<h1>VIAJES</h1>
{!! Breadcrumbs::render('viajes.netos.validar') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-validar inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <div class="row">
                <div class="col-md-12">
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
                            <th rowspan="2">Código</th>
                            <th rowspan="2">Fecha de Llegada</th>
                            <th rowspan="2">Hora de Llegada</th>
                            <th rowspan="2">Tiro</th>
                            <th rowspan="2">Camión</th>
                            <th rowspan="2">Origen</th>
                            <th rowspan="2">Material</th>
                            <th rowspan="2">Tiempo</th>
                            <th rowspan="2">Ruta</th>
                            <th rowspan="2">Distancia</th>
                            <th colspan="3">Tarifa</th>
                            <th rowspan="2">Importe</th>
                            <th rowspan="2">?</th>
                            <th rowspan="2">Validar</th>
                            <th rowspan="2"></th>
                        </tr>
                        <tr>
                            <th>1er Km</th>
                            <th>Km Sub.</th>
                            <th>Km Adc.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="viaje in viajes">
                            <td>@{{ viaje.Code }}</td>
                            <td>@{{ viaje.FechaLlegada }}</td>
                            <td>@{{ viaje.HoraLlegada }}</td>                            
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
                            <td>
                                <span v-if='viaje.Valido'>
                                    <i class="fa fa-flag" style="color: green"></i>
                                </span>
                                <span v-else>
                                    <i class="fa fa-flag" style="color: red"></i>
                                </span>
                            </td>
                            <td>
                                <a id="show-modal" @click="viaje.ShowModal = true">
                                    Validar     
                                </a>
                                <modal-validar v-if="viaje.ShowModal" @close="viaje.ShowModal = false">
                                    <h3 slot="header">Validar Viaje</h3>
                                    <div slot="body" class="form-horizontal">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label>Sindicato:</label>
                                                    <select v-model="viaje.Sindicato" class="form-control input-sm">
                                                        <option value>--SELECCIONE--</option>
                                                        @foreach($sindicatos as $sindicato)
                                                        <option value="{{ $sindicato->IdSindicato}}">{{ $sindicato->NombreCorto }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Empresa:</label>
                                                    <select v-model="viaje.Empresa" class="form-control input-sm">
                                                        <option value>--SELECCIONE--</option>
                                                        @foreach($empresas as $empresa)
                                                        <option value="{{ $empresa->IdEmpresa }}">{{ $empresa->razonSocial }}</option>
                                                        @endforeach
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
                                                        <input type="radio" value="1" v-model="viaje.Accion">
                                                    </div>
                                                    <div>
                                                        <label><i class="fa fa-close" style="color: red"></i> Denegar:</label>
                                                        <input type="radio" value="0" v-model="viaje.Accion">
                                                    </div>   
                                                </span>
                                                <span v-else>
                                                    <div >
                                                        <label><i class="fa fa-check" style="color: green"></i> Validar:</label>
                                                        <input type="radio" value="1" v-model="viaje.Accion" disabled="disabled">
                                                    </div>
                                                    <div>
                                                        <label><i class="fa fa-close" style="color: red"></i> Denegar:</label>
                                                        <input type="radio" value="0" v-model="viaje.Accion">
                                                    </div>   
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" slot="footer">
                                        <button class="btn btn-info btn-sm" @click="viaje.ShowModal = false">Cerrar</button>        
                                        <button class="btn btn-success btn-sm" @click="confirmarValidacion(viajes.indexOf(viaje))">
                                            <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i></span>
                                            <span v-else>Validar</span>
                                        </button>
                                    </div>
                                </modal-validar>
                            </td>
                            <td>
                                <span v-if="viaje.Imagenes.length">
                                    <button class="btn btn-xs btn-default" data-toggle="modal" v-bind:data-target="'.modal-lg-' + viaje.IdViajeNeto"><i class="fa fa-2x fa-photo"></i></button>
                                    <div v-bind:class="'modal fade modal-lg-' + viaje.IdViajeNeto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div v-bind:id="'carousel' + viaje.IdViajeNeto" class="carousel slide" data-ride="carousel">
                                                    <!-- Wrapper for slides -->
                                                    <div class="carousel-inner">
                                                        <div v-for="imagen in viaje.Imagenes" v-bind:class="itemClass(viaje.Imagenes.indexOf(imagen))">
                                                            <img class="img-responsive" v-bind:src="'data:image/png;base64,' + imagen.imagen">
                                                            <div class="carousel-caption"><h1>Imagen del viaje @{{ viaje.Imagenes.indexOf(imagen) + 1 }}</h1></div>
                                                        </div>
                                                    </div>
                                                        <!-- Controls -->
                                                    <a class="left carousel-control" v-bind:href="'#carousel' + viaje.IdViajeNeto" role="button" data-slide="prev">
                                                      <span class="glyphicon glyphicon-chevron-left"></span>
                                                    </a>
                                                    <a class="right carousel-control" v-bind:href="'#carousel' + viaje.IdViajeNeto" role="button" data-slide="next">
                                                      <span class="glyphicon glyphicon-chevron-right"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                                <span v-else>
                                    <button class="btn btn-xs btn-default" disabled="disabled"><i class="fa fa-2x fa-photo"></i></button>
                                </span>           
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </viajes-validar>
</div>