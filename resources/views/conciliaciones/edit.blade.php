@extends('layout')

@section('content')
@include('partials.errors')
<div id="app">
    <global-errors></global-errors>
    <conciliaciones-edit inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <h1>
                CONCILIACIONES
                @if($conciliacion->estado == 0)
                    <a href="{{ route('conciliaciones.destroy', $conciliacion->idconciliacion) }}" class="btn btn-danger btn-sm pull-right" @click="cancelar(0, $event)"><i class="fa fa-close"></i> CANCELAR</a>
                    <a href="{{ route('conciliaciones.update', $conciliacion->idconciliacion) }}" class="btn btn-success btn-sm pull-right" style="margin-right: 5px" @click="cerrar"><i class="fa fa-check"></i> CERRAR</a>
                @elseif($conciliacion->estado == 1)
                    <a href="{{ route('conciliaciones.destroy', $conciliacion->idconciliacion) }}" class="btn btn-danger btn-sm pull-right" @click="cancelar(1, $event)"><i class="fa fa-close"></i> CANCELAR</a>
                    <a href="{{ route('conciliaciones.update', $conciliacion->idconciliacion) }}" class="btn btn-success btn-sm pull-right" style="margin-right: 5px" @click="aprobar"><i class="fa fa-check"></i> APROBAR</a>
                @endif
            </h1>
            {!! Breadcrumbs::render('conciliaciones.edit', $conciliacion) !!}

             @if($conciliacion->estado == -1 || $conciliacion->estado == -2)
                <section id="cancelada">
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    DETALLES DE LA CANCELACIÓN
                                </div>
                                <div class="panel-body">
                                    <strong>Fecha y hora de cancelación: </strong>{{ $conciliacion->cancelacion->timestamp_cancelacion }}<br>
                                    <strong>Persona que canceló: </strong>{{ $conciliacion->cancelacion->user->present()->nombreCompleto }}<br>
                                    <strong>Motivo de la cancelación: </strong>{{ $conciliacion->cancelacion->motivo }}<br>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @elseif($conciliacion->estado == 0)
                <section id="conciliar">
                    <hr>
                    <h3>CONCILIAR VIAJES</h3>
                    {!! Form::open(['route' => ['conciliaciones.detalles.store', $conciliacion->idconciliacion], 'class' => 'form_buscar']) !!}
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
                                    <input class="form-control" type="text" name="code" placeholder="Código del Viaje">
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
                </section>
            @endif
            <section id="detalles" v-if="conciliacion.detalles.length">
                <hr>
                <ul id="detail-tabs" class="nav nav-tabs">
                    <li class="active tab-conciliacion"><a href="#details" data-toggle="tab">DETALLE DE CONCILIACIÓN</a></li>
                </ul>
                <div class="tab-content">
                    <div id="details" class="fade in active tab-pane table-responsive">
                        <table class="table table-striped table-bordered small">
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
                                <td>@{{ detalle.timestamp_llegada }}</td>
                                <td>@{{ detalle.camion }}</td>
                                <td>@{{ detalle.cubicacion_camion }}</td>
                                <td>@{{ detalle.material }}</td>
                                <td>@{{ detalle.importe }}</td>
                                <td>@{{ detalle.code }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- MODAL RESULTADOS -->
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
        </section>
    </conciliaciones-edit>
</div>

@stop