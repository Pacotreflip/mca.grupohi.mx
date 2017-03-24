<h1>VIAJES</h1>
{!! Breadcrumbs::render('viajes.netos.modificar') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-modificar inline-template>
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
                <table id="viajes_netos_modificar" v-tablefilter v-if="viajes.length" class="table table-condensed table-bordered table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2">Fecha de Llegada</th>
                            <th rowspan="2">Origen</th>
                            <th rowspan="2">Tiro</th>
                            <th rowspan="2">Camión</th>
                            <th rowspan="2">Cubic.</th>
                            <th rowspan="2">Material</th>
                            <th rowspan="2">Modificar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="viaje in viajes">
                            <td>@{{ viaje.FechaLlegada }}</td>
                            <td>@{{ viaje.Origen }}</td>
                            <td>@{{ viaje.Tiro }}</td>
                            <td>@{{ viaje.Camion }}</td>
                            <td>@{{ viaje.Cubicacion }}</td>
                            <td>@{{ viaje.Material }}</td>
                            
                            <td>
                                <a id="show-modal" @click="viaje.ShowModal = true">
                                    Modificar     
                                </a>
                                <modal-modificar v-if="viaje.ShowModal" @close="viaje.ShowModal = false">
                                    <h3 slot="header">Modificar Viaje Neto</h3>
                                    <div slot="body" class="form-horizontal">
                                        <div class="row">
                                            <div class="form-group">
                                                <label>Origen:</label>
                                                <select v-model="viaje.IdOrigen" class="form-control input-sm">
                                                    <option value>--SELECCIONE--</option>
                                                    @foreach($origenes as $origen)
                                                    <option value="{{ $origen->IdOrigen }}">{{ $origen->Descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Destino:</label>
                                                <select v-model="viaje.IdTiro" class="form-control input-sm">
                                                    <option value>--SELECCIONE--</option>
                                                    @foreach($tiros as $tiro)
                                                    <option value="{{ $tiro->IdTiro }}">{{ $tiro->Descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Cubicación Viaje:</label>
                                                <input type="text" v-model="viaje.Cubicacion"  class="form-control input-sm"  >
                                            </div>
                                            <div class="form-group">
                                                <label>Material:</label>
                                                <select v-model="viaje.IdMaterial" class="form-control input-sm">
                                                    <option value>--SELECCIONE--</option>
                                                    @foreach($materiales as $material)
                                                    <option value="{{ $material->IdMaterial }}">{{ $material->Descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" slot="footer">
                                        <button class="btn btn-info btn-sm" @click="viaje.ShowModal = false">Cerrar</button>        
                                        <button class="btn btn-success btn-sm" @click="confirmarModificacion(viajes.indexOf(viaje))">
                                            <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i></span>
                                            <span v-else>Modificar</span>
                                        </button>
                                    </div>
                                </modal-modificar>
                            </td>                                   
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </viajes-modificar>
</div>