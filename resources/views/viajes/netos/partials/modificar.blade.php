<h1>VIAJES</h1>
{!! Breadcrumbs::render('viajes.netos.modificar') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-modificar inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <h3>BUSCAR VIAJES</h3>
            {!! Form::open(['class' => 'form_buscar']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>FECHA INICIAL</label>
                        <input type="text" name="FechaInicial" v-datepicker class="fecha form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>FECHA FINAL</label>
                        <input type="text" name="FechaFinal" v-datepicker class="fecha form-control">
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

            <hr>
            <div class="table-responsive">
                <span v-if="cargando">
                    <div class="text-center">
                        <i class="fa fa-2x fa-spinner fa-spin"></i> Cargando Viajes...
                    </div>
                </span>
                <span v-if="viajes_netos.length">
                    <h3>RESULTADOS DE LA BÚSQUEDA</h3>
                    <table id="viajes_netos_modificar" v-tablefilter class="table table-condensed table-bordered table-hover small">
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
                            <tr v-for="viaje in viajes_netos">
                                <td>@{{ viaje.FechaLlegada }}</td>
                                <td>@{{ viaje.Origen }}</td>
                                <td>@{{ viaje.Tiro }}</td>
                                <td>@{{ viaje.Camion }}</td>
                                <td>@{{ viaje.CubicacionCamion }}</td>
                                <td>@{{ viaje.Material }}</td>

                                <td>
                                    <a id="show-modal" @click="showModal(viaje)">
                                        Modificar
                                    </a>
                                    <modal-modificar v-if="viaje.ShowModal" @close="viaje.ShowModal = false">
                                        <h3 slot="header">Modificar Viaje Neto</h3>
                                        <div slot="body" class="form-horizontal">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label>Origen:</label>
                                                    <select v-model="form.data.IdOrigen" class="form-control input-sm">
                                                        <option value>--SELECCIONE--</option>
                                                        @foreach($origenes as $origen)
                                                        <option value="{{ $origen->IdOrigen }}">{{ $origen->Descripcion }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Destino:</label>
                                                    <select v-model="form.data.IdTiro" class="form-control input-sm">
                                                        <option value>--SELECCIONE--</option>
                                                        @foreach($tiros as $tiro)
                                                        <option value="{{ $tiro->IdTiro }}">{{ $tiro->Descripcion }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cubicación Viaje:</label>
                                                    <input type="text" v-model="form.data.CubicacionCamion"  class="form-control input-sm"  >
                                                </div>
                                                <div class="form-group">
                                                    <label>Material:</label>
                                                    <select v-model="form.data.IdMaterial" class="form-control input-sm">
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
                                            <button class="btn btn-success btn-sm" @click="modificar(viaje)">
                                                <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i></span>
                                                <span v-else>Modificar</span>
                                            </button>
                                        </div>
                                    </modal-modificar>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </span>
            </div>
        </section>
    </viajes-modificar>
</div>