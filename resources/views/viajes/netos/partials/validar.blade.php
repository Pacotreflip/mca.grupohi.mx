<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.validar') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-validar inline-template>
        <section>
            <div class=" col-md-8 col-md-offset-2 rcorners">
                <legend class="text-center"><small><i class="fa fa-calendar"></i> RANGO DE FECHAS A CONSULTAR</small></legend>
                <form>
                    <div class="form-group">
                        <label class="control-label col-md-2">Fecha Inicial:</label>
                        <div class="col-md-3">
                            <input id="FechaInicial" @blur="setFechaInicial($event)" v-datepicker type="text" class="form-control fecha input-sm" v-model="fechas.FechaInicial">
                        </div>
                        <label class="control-label col-md-2">Fecha Final:</label>
                        <div class="col-md-3">
                            <input id="FechaFinal" @blur="setFechaFinal($event)" v-datepicker type="text" class="form-control fecha input-sm" v-model="fechas.FechaFinal">
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-success" value="Buscar" v-on:click="fetchViajes">
                    </div>
                </form>
            </div>
            <div class="table-responsive col-md-10 col-md-offset-1">
                <table id="viajes_netos_validar" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha Llegada</th>
                            <th>Hora Llegada</th>
                            <th>Camión</th>
                            <th>Tiro</th>
                            <th>Origen</th>
                            <th>Material</th>
                            <th>m<sup>3</sup></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="viaje in form.viajes">
                            <td>@{{ viaje.FechaLlegada }}</td>
                            <td>@{{ viaje.HoraLlegada }}</td>
                            <td>@{{ viaje.camion.Economico }}</td>
                            <td>@{{ viaje.tiro.Descripcion }}</td>
                            <td>@{{ viaje.origen.Descripcion }}</td>
                            <td>@{{ viaje.material.Descripcion }}</td>
                            <td>gg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </viajes-validar>
</div>
