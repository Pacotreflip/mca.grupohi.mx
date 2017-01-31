<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.validar') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-validar inline-template>
        <section>    
            <div class="form-inline">
              <div class="row">
                <select class="input-sm form-control" placeholder="Buscar Por" v-model="datosConsulta.tipo">
                    <option value>Buscar Por...</option>
                    <option value="dates">Rango de Fechas</option>
                    <option value="code">Código</option>
                </select>         
              </div>
                <br>
                <div class="row">
              <span v-show="datosConsulta.tipo == 'dates'">
              <div class="form-group">
                  <input type="text" v-model="datosConsulta.fechaInicial" v-datepicker class="fecha form-control input-sm" placeholder="Fecha de Inicio" @blur="setFechaInicial($event)">
              </div>
              <div class="form-group">
                <input type="text" v-model="datosConsulta.fechaFinal" v-datepicker class="fecha form-control input-sm" placeholder="Fecha de Fin" @blur="setFechaFinal($event)">
              </div>
              </span>
              <span v-show="datosConsulta.tipo == 'code'">
              <div class="form-group">
                  <input type="text" v-model="datosConsulta.code" class="form-control input-sm" placeholder="Código">
              </div>
              </span>
              <button v-if="datosConsulta.tipo" v-on:click="fetchViajes(datosConsulta.tipo)" class="btn btn-sm btn-primary" >Consultar</button>
                </div>
            </div>
            <hr>
            <br>
            <div class="table-responsive">
                <table class="table table-condensed">
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
                        <tr v-for="viaje in viajes">
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