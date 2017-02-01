<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.validar') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-validar inline-template>
        <section>
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
                        <button v-on:click="fetchViajes()" class="btn btn-sm btn-primary" >Consultar</button>
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
                <table v-if="viajes.length" class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Fecha de Llegada</th>
                            <th>Hora de Llegada</th>
                            <th>Tiro</th>
                            <th>Camión</th>
                            <th>m<sup>3</sup></th>
                            <th>Origen</th>
                            <th>Sindicato</th>
                            <th>Empresa</th>
                            <th>Material</th>
                            <th>Tiempo</th>
                            <th>Ruta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <span v-if="datosConsulta.tipo = 'code'">
                            <tr v-for="viaje in getViajesByCode">
                        </span>
                        <span v-else>
                            <tr v-for="viaje in getViajesByCode">
                        </span>
                            <td>@{{ viaje.FechaLlegada }}</td>
                            <td>@{{ viaje.HoraLlegada }}</td>
                            <td>@{{ viaje.Tiro }}</td>
                            <td>@{{ viaje.Camion }}</td>
                            <td>@{{ viaje.Cubicacion }}</td>
                            <td>@{{ viaje.Origen }}</td>
                            <td>@{{ viaje.Origen }}</td>
                            <td>@{{ viaje.Origen }}</td>
                            <td>@{{ viaje.Material }}</td>
                            <td>@{{ viaje.Tiempo }}</td>
                            <td>@{{ viaje.Ruta }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </viajes-validar>
</div>