@extends('layout')

@section('content')
    <h1>VIAJES</h1>
    {!! Breadcrumbs::render('viajes_netos.index') !!}
    <hr>
    <div id="app">
        <global-errors></global-errors>
        <viajes-index inline-template>
            <section>
                <div id="partials_errors">
                @include('partials.errors')
                </div>
                <app-errors v-bind:form="form"></app-errors>
                <h3>BUSCAR VIAJES</h3>
                {!! Form::open(['class' => 'form_buscar']) !!}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>FECHA INICIAL (*)</label>
                            <input type="text" name="FechaInicial" class="form-control" v-datepicker>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>FECHA FINAL (*)</label>
                            <input type="text" name="FechaFinal" class="form-control" v-datepicker>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>TIPO DE VIAJES (*)</label>
                            <select name="Tipo[]" class="form-control" multiple="multiple" v-select2>
                                <optgroup label="CARGADOS MANUALMENTE">
                                    <option value="CM_C">Manuales - Cargados</option>
                                    <option value="CM_A">Manuales - Autorizados (Pend. Validar)</option>
                                    <option value="CM_R">Manuales - Rechazados</option>
                                    <option value="CM_V">Manuales - Validados</option>
                                    <option value="CM_D">Manuales - Denegados</option>
                                </optgroup>
                                <optgroup label="CARGADOS DESDE APLICACIÓN MÓVIL">
                                    <option value="M_V">Móviles - Validados</option>
                                    <option value="M_A">Móviles - Pendientes de Validar</option>
                                    <option value="M_D">Móviles - Denegados</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit" @click="buscar">
                        <span v-if="cargando"><i class="fa fa-spinner fa-spin"></i> Buscando</span>
                        <span v-else><i class="fa fa-search"></i> Buscar</span>
                    </button>
                    <button class="btn  btn-info" @click="pdf"><i class="fa fa-file-pdf-o"></i> VER PDF</button>
                </div>
                <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
                {!! Form::close() !!}
                <hr>
                <section v-if="viajes_netos.length" id="results">
                    <h3>
                        RESULTADOS DE LA BÚSQUEDA
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered small">
                            <thead>
                            <tr>
                                <th style="text-align: center"> # </th>
                                <th style="text-align: center"> Tipo </th>
                                <th style="text-align: center"> Camión </th>
                                <th style="text-align: center"> Ticket (Código) </th>
                                <th style="text-align: center"> Fecha y Hora de Llegada </th>
                                <th style="text-align: center"> Origen</th>
                                <th style="text-align: center"> Tiro </th>
                                <th style="text-align: center"> Material </th>
                                <th style="text-align: center"> Cubicación	</th>
                                <th style="text-align: center"> Importe </th>
                                <th style="text-align: center"> Registró </th>
                                <th style="text-align: center"> Autorizó </th>
                                <th style="text-align: center"> Validó </th>
                                <th style="text-align: center"> Estado </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(viaje_neto, index) in viajes_netos">
                                <td>@{{ index + 1 }}</td>
                                <td>@{{ viaje_neto.tipo }}</td>
                                <td>@{{ viaje_neto.camion }}</td>
                                <td>@{{ viaje_neto.codigo }}</td>
                                <td>@{{ viaje_neto.timestamp_llegada }}</td>
                                <td>@{{ viaje_neto.origen }}</td>
                                <td>@{{ viaje_neto.tiro }}</td>
                                <td>@{{ viaje_neto.material }}</td>
                                <td>@{{ viaje_neto.cubicacion }}</td>
                                <td>@{{ viaje_neto.importe }}</td>
                                <td>@{{ viaje_neto.registro }}</td>
                                <td>@{{ viaje_neto.autorizo }}</td>
                                <td>@{{ viaje_neto.valido }}</td>
                                <td>@{{ viaje_neto.estado }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </viajes-index>
    </div>
@stop