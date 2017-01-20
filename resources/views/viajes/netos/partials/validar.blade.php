<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.validar') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-validar inline-template>
        <section>
            <div class="form-horizontal col-md-4 col-md-offset-4 rcorners">
                <legend class="text-center"><small><i class="fa fa-calendar"></i> RANGO DE FECHAS A CONSULTAR</small></legend>
                <form>
                    <div class="form-group">
                        <label class="control-label col-md-3">Fecha Inicial:</label>
                        <div class="col-md-9">
                            <input id="FechaInicial" @blur="setFechaInicial($event)" v-datepicker type="text" class="form-control fecha input-sm" v-model="fechas.FechaInicial">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Fecha Final:</label>
                        <div class="col-md-9">
                            <input id="FechaFinal" @blur="setFechaFinal($event)" v-datepicker type="text" class="form-control fecha input-sm" v-model="fechas.FechaFinal">
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-success" value="Buscar" v-on:click="fetchViajes">
                    </div>
                </form>
            </div>
            <div class="table-responsive col-md-10 col-md-offset-1">
                <table id="viajes_validar" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha Llegada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="viaje in form.viajes">
                            <td>@{{ viaje.FechaLlegada }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </viajes-validar>
</div>
