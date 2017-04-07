@extends('layout')

@section('content')
<h1>VIAJES</h1>
{!! Breadcrumbs::render('viajes_netos.index') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-index inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <h3>BUSCAR VIAJES</h3>
            {!! Form::open(['class' => 'form_buscar']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>TIPO DE VIAJES (*)</label>
                        <select name="Tipo" v-model="form.tipo" class="form-control">
                            <option value="2">TODOS</option>
                            <option value="0">CARGADOS MANUALMENTE</option>
                            <option value="1">CARGADOS DESDE APLICACIÓN MÓVIL</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ESTADO</label>
                        <select name="Estatus" class="form-control">
                            <option value>TODOS</option>
                            <option v-if="form.tipo == '0'" value="29">CARGADOS</option>
                            <option v-if="form.tipo == '0'" value="20">PENDIENTES DE VALIDAR</option>
                            <option v-if="form.tipo == '0'"  value="22">NO AUTORIZADOS (RECHAZADOS)</option>
                            <option v-if="form.tipo == '0'"  value="211">VALIDADOS</option>
                            <option v-if="form.tipo == '0'"  value="21">NO VALIDADOS (DENEGADOS)</option>
                            <option v-if="form.tipo != '0'"  value="1">VALIDADOS</option>
                            <option v-if="form.tipo != '0'"  value="0">PENDIENTES DE VALIDAR</option>
                            </span>
                        </select>
                    </div>
                </div></div>
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
            <div class="form-group">
                <button class="btn btn-primary" type="submit" @click="buscar">
                    <span v-if="cargando"><i class="fa fa-spinner fa-spin"></i></span>
                    <span v-else>Buscar</span>
                </button>
            </div>
            <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
            {!! Form::close() !!}
            <hr>
            <section v-if="viajes_netos.length" id="results">
                <h3>RESULTADOS DE LA BÚSQUEDA</h3>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered small">
                        <thead>
                            <tr>
                                <th style="text-align: center"> Tipo </th>
                                <th style="text-align: center"> Camión </th>
                                <th style="text-align: center"> Ticket (Código) </th>
                                <th style="text-align: center"> Fecha y Hora de Llegada </th>
                                <th style="text-align: center"> Registro </th>
                                <th style="text-align: center"> Origen</th>
                                <th style="text-align: center"> Tiro </th>
                                <th style="text-align: center"> Material </th>
                                <th style="text-align: center"> Cubicación	</th>
                                <th style="text-align: center"> Importe </th>
                                <th style="text-align: center"> Estado </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="viaje_neto in viajes_netos">
                                <td>@{{ viaje_neto.tipo }}</td>
                                <td>@{{ viaje_neto.camion }}</td>
                                <td>@{{ viaje_neto.codigo }}</td>
                                <td>@{{ viaje_neto.timestamp_llegada }}</td>
                                <td>@{{ viaje_neto.registro }}</td>
                                <td>@{{ viaje_neto.origen }}</td>
                                <td>@{{ viaje_neto.tiro }}</td>
                                <td>@{{ viaje_neto.material }}</td>
                                <td>@{{ viaje_neto.cubicacion }}</td>
                                <td>@{{ viaje_neto.importe }}</td>
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