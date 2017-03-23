@extends('layout')

@section('content')
@include('partials.errors')
<div id="app">
    <global-errors></global-errors>
    <conciliaciones-edit inline-template>
        <section>
            <input name="id_conciliacion" type="hidden" id="id_conciliacion"  value="{{ route('conciliaciones.show', $conciliacion) }}">
            <h1>
                CONCILIACIONES
                @if(count($conciliacion->conciliacionDetalles->where('estado', 1)) && Auth::user()->can(['ver-pdf']))
                    <a href="{{ route('pfd.conciliacion', $conciliacion) }}" class="btn btn-default btn-sm pull-right" style="margin-left: 5px"><i class="fa fa-file-pdf-o"></i> VER PDF</a>
                @endif
                @if($conciliacion->estado == 0)
                    @if (Auth::user()->can(['cancelar-conciliacion']))
                    <a href="{{ route('conciliaciones.destroy', $conciliacion->idconciliacion) }}" class="btn btn-danger btn-sm pull-right" @click="cancelar($event)"><i class="fa fa-close"></i> CANCELAR</a>
                    @endif
                    @if (Auth::user()->can(['cerrar-conciliacion']))
                    <a href="{{ route('conciliaciones.update', $conciliacion->idconciliacion) }}" class="btn btn-success btn-sm pull-right" style="margin-right: 5px" @click="cerrar"><i class="fa fa-check"></i> CERRAR</a>
                    @endif
                @elseif($conciliacion->estado == 1)
                    @if (Auth::user()->can(['reabrir-conciliacion']))
                    <a href="{{ route('conciliaciones.edit', [$conciliacion, 'action' => 'reabrir']) }}" class="btn btn-default btn-sm pull-right" style="margin-right: 5px" @click="reabrir"><i class="fa fa-undo"></i> RE-ABRIR</a>
                    @endif
                    @if (Auth::user()->can(['cancelar-conciliacion']))
                    <a href="{{ route('conciliaciones.destroy', $conciliacion->idconciliacion) }}" class="btn btn-danger btn-sm pull-right" @click="cancelar($event)"><i class="fa fa-close"></i> CANCELAR</a>
                    @endif
                    @if (Auth::user()->can(['aprobar-conciliacion']))
                    <a href="{{ route('conciliaciones.update', $conciliacion->idconciliacion) }}" class="btn btn-success btn-sm pull-right" style="margin-right: 5px" @click="aprobar"><i class="fa fa-check"></i> APROBAR</a>
                    @endif
                @elseif($conciliacion->estado == 2)
                    @if (Auth::user()->can(['cancelar-conciliacion']))
                    <a href="{{ route('conciliaciones.destroy', $conciliacion->idconciliacion) }}" class="btn btn-danger btn-sm pull-right" @click="cancelar($event)"><i class="fa fa-close"></i> CANCELAR</a>
                    @endif
                @endif
            </h1>
            {!! Breadcrumbs::render('conciliaciones.edit', $conciliacion) !!}
            <app-errors v-bind:form="form"></app-errors>

            <span v-if="fetching">
                <div class="text-center"><i class="fa fa-spinner fa-pulse fa-2x"></i> <big>CARGANDO CONCILIACIÓN</big></div>
            </span>
            <span v-else>
                @if($conciliacion->estado == 0)
                <section id="conciliar">
                        <hr>
                        <h3>CONCILIAR VIAJES</h3>
                        {!! Form::open(['route' => ['conciliaciones.detalles.store', $conciliacion->idconciliacion], 'class' => 'form_buscar', 'files' => true]) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>TIPO DE BÚSQUEDA</label>
                                    {!! Form::select('Tipo', [
                                    '' => '--SELECCIONE--',
                                    '1' => 'BÚSQUEDA POR CÓDIGO',
                                    '2' => 'BÚSQUEDA AVANZADA',
                                    '3' => 'CARGAR EXCEL'
                                     ], '1', ['v-model' => 'tipo', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <span v-show="tipo == '1'">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label>CÓDIGO DEL VIAJE</label>
                                        <input class="form-control ticket" type="text" name="code" placeholder="Código del Viaje">
                                        <span class="input-group-btn" style="padding-top: 25px">
                                            <button class="btn btn-primary" type="submit" @click="agregar">
                                            <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i></span>
                                            <span v-else>Agregar</span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </span>
                            <span v-show="tipo == '2'">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>CAMIÓN</label>
                                        {!! Form::select('IdCamion', $camiones, null, ['class' => 'form-control', 'placeholder' => '--SELECCIONE--']) !!}
                                    </div>
                                </div>
                            </span>
                            <span v-show="tipo == '3'">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>CARGAR EXCEL</label>
                                        <input v-fileinput type="file" name="excel" class="file-loading">
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
                                <button class="btn btn-primary" type="submit" @click="buscar">
                                <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i></span>
                                <span v-else>Buscar</span>
                                </button>
                            </div>
                        </span>
                        {!! Form::close() !!}
                    </section>
                @endif
                <section id="info">
                    <hr>
                    <div class="row">
                        @if($conciliacion->estado == -1 || $conciliacion->estado == -2)
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
                        @endif
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    DETALLES DE LA CONCILIACIÓN
                                </div>
                                <div class="panel-body">
                                    <strong>Empresa: </strong>@{{ conciliacion.empresa }}<br>
                                    <strong>Sindicato: </strong>@{{ conciliacion.sindicato }}<br>
                                    <strong>Número de Viajes: </strong>@{{ conciliados.length }}<br>
                                    <strong>Volúmen: </strong>@{{ conciliacion.volumen }}<br>
                                    <strong>Importe: </strong>@{{ conciliacion.importe }}<br>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="detalles" v-if="conciliacion.detalles.length">
                <hr>
                <ul id="detail-tabs" class="nav nav-tabs">
                    <li v-if="conciliados.length" class="active tab-conciliacion"><a href="#details" data-toggle="tab">VIAJES CONCILIADOS</a></li>
                    <li v-if="cancelados.length" v-bind:class="(cancelados.length && !conciliados.length) ? 'active' : '' + 'tab-conciliacion'"><a href="#cancelados" data-toggle="tab">VIAJES CANCELADOS</a></li>
                </ul>
                <div class="tab-content">
                    <div id="details" v-if="conciliados.length" class="fade in active tab-pane table-responsive">
                        <table class="table table-striped table-bordered small">
                            <thead>
                            <tr>
                                <th>Fecha y Hora de Llegada</th>
                                <th>Camión</th>
                                <th>Cubicación</th>
                                <th>Material</th>
                                <th>Importe</th>
                                <th>Ticket (Código)</th>
                                @if($conciliacion->estado == 0 && (Auth::user()->can(['eliminar-viaje-conciliacion'])))
                                <th style="width: 30px"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="detalle in conciliados">
                                <td>@{{ detalle.timestamp_llegada }}</td>
                                <td>@{{ detalle.camion }}</td>
                                <td style="text-align: right">
                                    @if($conciliacion->estado == 0)
                                    <a href="#" @click="cambiar_cubicacion(detalle)" style="text-decoration: underline">@{{ detalle.cubicacion_camion }}</a>
                                    @else
                                        @{{ detalle.cubicacion_camion }}
                                    @endif
                                </td>
                                <td>@{{ detalle.material }}</td>
                                <td style="text-align: right">@{{ detalle.importe }}</td>
                                <td>@{{ detalle.code }}</td>
                                @if($conciliacion->estado == 0 && (Auth::user()->can(['eliminar-viaje-conciliacion'])) )
                                <td>
                                    <button class="btn btn-xs btn-danger" @click="eliminar_detalle(detalle.idconciliacion_detalle)"><i class="fa fa-close"></i></button>
                                </td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="cancelados" v-bind:class="(cancelados.length && !conciliados.length) ? 'active' : '' + ' fade in tab-pane table-responsive'">
                        <table class="table table-striped table-bordered small">
                            <thead>
                            <tr>
                                <th>Fecha y Hora de Llegada</th>
                                <th>Camión</th>
                                <th>Cubicación</th>
                                <th>Material</th>
                                <th>Importe</th>
                                <th>Ticket (Código)</th>
                                <th>Fecha Cancelación</th>
                                <th>Persona que Canceló</th>
                                <th>Motivo de Cancelación</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="detalle in cancelados">
                                <td>@{{ detalle.timestamp_llegada }}</td>
                                <td>@{{ detalle.camion }}</td>
                                <td style="text-align: right">@{{ detalle.cubicacion_camion }}</td>
                                <td>@{{ detalle.material }}</td>
                                <td style="text-align: right">@{{ detalle.importe }}</td>
                                <td>@{{ detalle.code }}</td>
                                <td>@{{ detalle.cancelacion.timestamp }}</td>
                                <td>@{{ detalle.cancelacion.cancelo }}</td>
                                <td>@{{ detalle.cancelacion.motivo }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            </span>
            <!-- MODAL RESULTADOS -->
            <div class="modal fade" id="resultados" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Resultados de la Búsqueda</h4>
                        </div>
                        <div class="modal-body">
                            {!! Form::open(['route' => ['conciliaciones.detalles.store', $conciliacion->idconciliacion], 'class' => 'form_registrar']) !!}
                            <span v-if="resultados.length">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <input type="hidden" name="Tipo" value="2">
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
