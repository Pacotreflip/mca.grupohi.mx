<h1>VIAJES</h1>
{!! Breadcrumbs::render('viajes.revertir') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-revertir inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <hr>
            <h3>BUSCAR VIAJES</h3>
            {!! Form::open(['class' => 'form_buscar']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>FECHA INICIAL</label>
                        <input class="form-control" type="text" name="FechaInicial" v-datepicker>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>FECHA FINAL</label>
                        <input class="form-control" type="text" name="FechaFinal" v-datepicker>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit" @click="buscar">
                <span v-if="cargando"><i class="fa fa-spinner fa-spin"></i></span>
                <span v-else>Buscar</span>
                </button>
            </div>
            {!! Form::close() !!}
            <span v-if="cargando">
                <div class="text-center">
                    <i class="fa fa-2x fa-spinner fa-spin"></i> Cargando Viajes...
                </div>
            </span>
            <span v-if="viajes.length">
                <hr>
                <div class="table-responsive">
                    <table id="viajes_revertir" class="table table-hover small" v-tablefilter>
                        <thead>
                        <tr>
                            <th>Fecha de Llegada</th>
                            <th>Hora de Llegada</th>
                            <th>Origen</th>
                            <th>Tiro</th>
                            <th>Camión</th>
                            <th>Cubic.</th>
                            <th>Material</th>
                            <th>Código (Ticket)</th>
                            <th>Modificar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="viaje in viajes">
                            <td>@{{ viaje.FechaLlegada }}</td>
                            <td>@{{ viaje.HoraLlegada }}</td>
                            <td>@{{ viaje.Origen  }}</td>
                            <td>@{{ viaje.Tiro  }}</td>
                            <td>@{{ viaje.Camion  }}</td>
                            <td>@{{ viaje.Cubicacion  }}</td>
                            <td>@{{ viaje.Material }}</td>
                            <td>@{{ viaje.Codigo }}</td>
                            <td>
                                <span v-if="viaje.Estatus != -1">
                                <a href="#" @click="revertir(viaje)" style="text-decoration: underline">Revertir</a>
                                </span>
                                <span v-else>
                                    <p style="color: #ff0000;">Revertido</p>
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </span>
        </section>
    </viajes-revertir>
</div>