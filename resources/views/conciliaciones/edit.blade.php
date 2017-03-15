@extends('layout')

@section('content')
<h1>CONCILIACIONES</h1>
{!! Breadcrumbs::render('conciliaciones.edit', $conciliacion) !!}
<hr>
@include('partials.errors')
<div id="app">
    <global-errors></global-errors>
    <conciliaciones-edit inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            {!! Form::open(['class' => 'form_buscar']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>TIPO DE BÚSQUEDA</label>
                        {!! Form::select('Tipo', ['' => '--SELECCIONE--','1' => 'BÚSQUEDA POR CÓDIGO', '2' => 'BÚSQUEDA AVANZADA'], '1', ['v-model' => 'tipo', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <span v-if="tipo == '1'">
                    <div class="col-md-6">
                        <div class="input-group">
                            <label>CÓDIGO DEL VIAJE</label>
                            <input class="form-control" type="text" name="Code" placeholder="Código del Viaje">
                            <span class="input-group-btn" style="padding-top: 25px">
                                <button class="btn btn-success" type="submit" @click="agregar">Agregar</button>
                            </span>
                        </div>
                    </div>
                </span>
                <span v-if="tipo == '2'">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>CAMIÓN</label>
                            {!! Form::select('IdCamion', $camiones, null, ['class' => 'form-control', 'placeholder' => '--SELECCIONE--']) !!}
                        </div>
                    </div>
                </span>
                <span v-else>
                </span>
            </div>
            <span v-if="tipo == '2'">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="FechaInicial">FECHA INICIAL</label>
                            <input name="FechaInicial" type="text" class="form-control"  v-datepicker >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="FechaFinal">FECHA FINAL</label>
                            <input name="FechaFinal" type="text" class="form-control" v-datepicker >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="Buscar" @click="buscar">
                </div>
            </span>
            {!! Form::close() !!}

            <div class="modal fade" id="resultados" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Resultados de la Búsqueda</h4>
                        </div>
                        <div class="modal-body">
                            <span v-if="resultados.length">
                                <div class="table-responsive" style="max-height: 300px;">
                                    {!! Form::open(['route' => ['conciliaciones.detalles.store', $conciliacion->idconciliacion], 'class' => 'form_registrar']) !!}
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Agregar</th>
                                            <th>Fecha y Hora de Llegada</th>
                                            <th>Camión</th>
                                            <th>Cubicación</th>
                                            <th>Material</th>
                                            <th>Importe</th>
                                            <th>Ticket (Código)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="viaje in resultados">
                                            <td>
                                                <input type="checkbox" v-bind:name="'idviaje['+viaje.id+']'" v-bind:value="viaje.id">
                                            </td>
                                            <td>@{{ viaje.timestamp_llegada }}</td>
                                            <td>@{{ viaje.camion }}</td>
                                            <td style="text-align: right">@{{ viaje.cubicacion_camion }}</td>
                                            <td>@{{ viaje.material }}</td>
                                            <td style="text-align: right">@{{ viaje.importe }}</td>
                                            <td>@{{ viaje.code }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    {!! Form::close() !!}
                                </div>
                            </span>
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="confirmarRegistro">Guardar Cambios</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <hr>
            <h3>DETALLES DE LA CONCILIACIÓN</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Fecha y Hora de Llegada</th>
                        <th>Camión</th>
                        <th>Cubicación</th>
                        <th>Material</th>
                        <th>Importe</th>
                        <th>Ticket (Código)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="detalle in conciliacion.detalles">
                        <td>@{{ detalle.timestamp }}</td>
                        <td>@{{ detalle.idviaje }}</td>
                        <td>@{{  }}</td>
                        <td>@{{  }}</td>
                        <td>@{{  }}</td>
                        <td>@{{  }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </conciliaciones-edit>
</div>
@stop