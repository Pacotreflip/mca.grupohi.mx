@extends('layout')

@section('content')
@include('partials.errors')
<div id="app">
    <global-errors></global-errors>
    <conciliaciones-edit inline-template>
        <section>
            <span v-if="fetching">
                <div class="text-center"><i class="fa fa-spinner fa-pulse fa-2x"></i> <big>CARGANDO CONCILIACIÓN</big></div>
            </span>
            <span v-else v-cloak>
            <input name="id_conciliacion" type="hidden" id="id_conciliacion"  value="{{ route('conciliaciones.show', $conciliacion) }}">
            <h1>
                CONCILIACIONES <small>(@{{ conciliacion.estado_str }})</small>

                @if(Auth::user()->can(['ver-pdf']))
                <a  href="{{ route('pfd.conciliacion', $conciliacion) }}" target="_blank" class="btn btn-default btn-sm pull-right" style="margin-left: 5px"><i class="fa fa-file-pdf-o"></i> VER PDF</a>
                @endif

                <span v-if="conciliacion.estado == 0">
                    @if (Auth::user()->can(['cancelar-conciliacion']))
                    <a href="{{ route('conciliaciones.destroy', $conciliacion->idconciliacion) }}" class="btn btn-danger btn-sm pull-right" @click="cancelar($event)"><i class="fa fa-close"></i> CANCELAR</a>
                    @endif
                    @if (Auth::user()->can(['cerrar-conciliacion']))
                    <a href="{{ route('conciliaciones.update', $conciliacion->idconciliacion) }}" class="btn btn-success btn-sm pull-right" style="margin-right: 5px" @click="cerrar"><i class="fa fa-check"></i> CERRAR</a>
                    @endif
                </span>
                <span v-else-if="conciliacion.estado == 1">
                    @if (Auth::user()->can(['reabrir-conciliacion']))
                    <a href="{{ route('conciliaciones.edit', [$conciliacion, 'action' => 'reabrir']) }}" class="btn btn-default btn-sm pull-right" style="margin-right: 5px" @click="reabrir"><i class="fa fa-undo"></i> RE-ABRIR</a>
                    @endif
                    @if (Auth::user()->can(['cancelar-conciliacion']))
                    <a href="{{ route('conciliaciones.destroy', $conciliacion->idconciliacion) }}" class="btn btn-danger btn-sm pull-right" @click="cancelar($event)"><i class="fa fa-close"></i> CANCELAR</a>
                    @endif
                    @if (Auth::user()->can(['aprobar-conciliacion']))
                    <a href="{{ route('conciliaciones.update', $conciliacion->idconciliacion) }}" class="btn btn-success btn-sm pull-right" style="margin-right: 5px" @click="aprobar"><i class="fa fa-check"></i> APROBAR</a>
                    @endif
                </span>
                <span v-else-if="conciliacion.estado == 2">
                    @if (Auth::user()->can(['cancelar-conciliacion']))
                    <a href="{{ route('conciliaciones.destroy', $conciliacion->idconciliacion) }}" class="btn btn-danger btn-sm pull-right" @click="cancelar($event)"><i class="fa fa-close"></i> CANCELAR</a>
                    @endif
                </span>
            </h1>
            {!! Breadcrumbs::render('conciliaciones.edit', $conciliacion) !!}
            <app-errors v-bind:form="form"></app-errors>
                <span v-if="conciliacion.estado == 0">
                    <section id="conciliar">
                        <hr>
                        <h3>CONCILIAR VIAJES</h3>
                        {!! Form::open(['route' => ['conciliaciones.detalles.store', $conciliacion->idconciliacion], 'class' => 'form_buscar', 'files' => true]) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>TIPO DE BÚSQUEDA (*)</label>
                                    @if (Auth::user()->has(['conciliacion_historico'])) 
                                    {!! Form::select('Tipo', [
                                    '' => '--SELECCIONE--',
                                    '1' => 'BÚSQUEDA POR CÓDIGO',
                                    '2' => 'BÚSQUEDA AVANZADA',
                                    '3' => 'CARGAR EXCEL',
                                    '4' => 'CARGAR EXCEL COMPLETA'
                                     ], '1', ['v-model' => 'tipo', 'class' => 'form-control']) !!}
                                     @else
                                     {!! Form::select('Tipo', [
                                    '' => '--SELECCIONE--',
                                    '1' => 'BÚSQUEDA POR CÓDIGO',
                                    '2' => 'BÚSQUEDA AVANZADA',
                                    '3' => 'CARGAR EXCEL',
                                     ], '1', ['v-model' => 'tipo', 'class' => 'form-control']) !!}
                                     @endif
                                </div>
                            </div>
                            <span v-show="tipo == '1'">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label>CÓDIGO DEL VIAJE (*)</label>
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
                            <span v-show="tipo == '3' || tipo== '4'">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>CARGAR EXCEL (*)</label>
                                        <input v-fileinput type="file" name="excel" class="file-loading">
                                    </div>
                                </div>
                            </span>
                        </div>
                        <span v-if="tipo == '2'">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="FechaInicial">FECHA INICIAL (*)</label>
                                        <input name="FechaInicial" type="text" class="form-control"  v-datepicker >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="FechaFinal">FECHA FINAL (*)</label>
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
                    <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
                    {!! Form::close() !!}
                    </section>
                </span>
                <section id="info">
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    DETALLES DE LA CONCILIACIÓN
                                </div>
                                <div class="panel-body">
                                    <strong>Fecha: </strong>@{{ conciliacion.fecha }}<br>
                                    <button v-if="conciliacion.estado == 0" data-toggle="modal" data-target="#detalles_conciliacion" class="pull-right btn btn-xs btn-primary"><i class="fa fa-edit"></i></button>
                                    <strong>Folio: </strong>@{{ conciliacion.folio }}
                                    <hr>
                                    <strong>Rango de Fechas: </strong>@{{ conciliacion.rango }}<br>
                                    <strong>Empresa: </strong>@{{ conciliacion.empresa }}<br>
                                    <strong>Sindicato: </strong>@{{ conciliacion.sindicato }}<br>
                                    <strong>Número de Viajes: </strong>@{{ conciliados ? conciliados.length : 0 }}<br>
                                    <strong>Volúmen: </strong>@{{ conciliacion.volumen }} m<sup>3</sup><br>
                                    <strong>Importe: </strong>$ @{{ conciliacion.importe }}<br>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel panel-heading">
                                    DETALLES DE VIAJES MANUALES
                                </div>
                                <div class="panel-body">
                                    <strong>Importe de viajes manuales: </strong>$ @{{ conciliacion.importe_viajes_manuales }} (@{{ conciliacion.porcentaje_importe_viajes_manuales }}%)<br>
                                    <strong>Volúmen de viajes manuales: </strong>@{{ conciliacion.volumen_viajes_manuales }} m<sup>3</sup> (@{{ conciliacion.porcentaje_volumen_viajes_manuales }}%)<br>
                                </div>
                            </div>
                        </div>
                        <span v-if="conciliacion.estado == -1  || conciliacion.estado == -2">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        DETALLES DE LA CANCELACIÓN
                                    </div>
                                    <div class="panel-body">
                                        <strong>Fecha y hora de cancelación: </strong>@{{ conciliacion.cancelacion.timestamp }}<br>
                                        <strong>Persona que canceló: </strong>@{{ conciliacion.cancelacion.cancelo }}<br>
                                        <strong>Motivo de la cancelación: </strong>@{{ conciliacion.cancelacion.motivo }}<br>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                </section>
                <section id="detalles" v-if="conciliacion.detalles.length || conciliacion.detalles_nc.length">
                <hr>
                <ul id="detail-tabs" class="nav nav-tabs">
                    <li v-if="conciliados.length" class="active tab-conciliacion"><a href="#conciliados_details" data-toggle="tab">VIAJES CONCILIADOS</a></li>
                    <li v-if="cancelados.length" v-bind:class="(cancelados.length && !conciliados.length) ? 'active' : '' + 'tab-conciliacion'"><a href="#cancelados_details" data-toggle="tab">VIAJES CANCELADOS</a></li>
                    <li v-if="conciliacion.detalles_nc.length" v-bind:class="(!cancelados.length && !conciliados.length && conciliacion.detalles_nc.length) ? 'active' : '' + 'tab-conciliacion'"><a href="#no_conciliados_details" data-toggle="tab">VIAJES NO CONCILIADOS</a></li>

                </ul>
                <div class="tab-content">
                    <div id="conciliados_details" v-if="conciliados.length" class="fade in active tab-pane table-responsive">
                        <table  class="table table-hover table-bordered small">
                            <thead>
                                <tr v-if="manuales.length">
                                    <td v-bind:colspan="conciliacion.estado == 0 ? '8' : '7'" style="text-align: center">
                                        <strong>VIAJES CARGADOS MANUALMENTE</strong>
                                    </td>
                                </tr>
                                <tr v-if="manuales.length">
                                    <th style="text-align: center">Camión</th>
                                    <th style="text-align: center">Ticket (Código)</th>
                                    <th style="text-align: center">Registró</th>
                                    <th style="text-align: center">Fecha y Hora de Llegada</th>
                                    <th style="text-align: center">Material</th>
                                    <th style="text-align: center">Cubicación</th>
                                    <th style="text-align: center">Importe</th>
                                    @if(Auth::user()->can(['eliminar-viaje-conciliacion']))
                                    <th style="text-align: center" v-if="conciliacion.estado == 0" style="width: 30px"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="detalle in manuales">
                                    <td>@{{ detalle.camion }}</td>
                                    <td>@{{ detalle.code }}</td>
                                    <td>@{{ detalle.registro }}</td>
                                    <td>@{{ detalle.timestamp_llegada }}</td>
                                    <td>@{{ detalle.material }}</td>
                                    <td style="text-align: right">
                                        <span v-if="conciliacion.estado == 0">
                                            <a href="#" @click="cambiar_cubicacion(detalle)" style="text-decoration: underline">@{{ detalle.cubicacion_camion }} m<sup>3</sup></a>
                                        </span>
                                        <span v-else>
                                            @{{ detalle.cubicacion_camion }} m<sup>3</sup>
                                        </span>
                                    </td>
                                    <td style="text-align: right">$ @{{ detalle.importe }}</td>
                                        @if(Auth::user()->can(['eliminar-viaje-conciliacion']))
                                        <td v-if="conciliacion.estado == 0">
                                            <button class="btn btn-xs btn-danger" @click="eliminar_detalle(detalle.idconciliacion_detalle)"><i class="fa fa-close"></i></button>
                                        </td>
                                        @endif
                                </tr>
                                <tr v-if="manuales.length">
                                    <td colspan="5">
                                        <strong>TOTAL VIAJES MANUALES</strong>
                                    </td>
                                    <td style="text-align: right"><strong>@{{ conciliacion.volumen_viajes_manuales }} m<sup>3</sup></strong></td>
                                    <td style="text-align: right"><strong>$ @{{ conciliacion.importe_viajes_manuales }}</strong></td>
                                </tr>

                                <!-- VIAJES MOVILES-->
                                <tr><td v-if="manuales.length" v-bind:colspan="conciliacion.estado == 0 ? '7' : '6'"></td></tr>
                                <tr v-if="moviles.length">
                                    <th v-bind:colspan="conciliacion.estado == 0 ? '8' : '7'" style="text-align: center">
                                        <strong>VIAJES CARGADOS DESDE APLICACIÓN MÓVIL</strong>
                                    </th>
                                </tr>
                                <tr v-if="moviles.length">
                                    <th style="text-align: center">Camión</th>
                                    <th style="text-align: center">Ticket (Código)</th>
                                    <th style="text-align: center">Registró</th>
                                    <th style="text-align: center">Fecha y Hora de Llegada</th>
                                    <th style="text-align: center">Material</th>
                                    <th style="text-align: center">Cubicación</th>
                                    <th style="text-align: center">Importe</th>
                                    @if(Auth::user()->can(['eliminar-viaje-conciliacion']))
                                        <th v-if="conciliacion.estado == 0" style="width: 30px"></th>
                                    @endif
                                </tr>
                                <tr v-for="detalle in moviles">
                                    <td>@{{ detalle.camion }}</td>
                                    <td>@{{ detalle.code }}</td>
                                    <td>@{{ detalle.registro }}</td>
                                    <td>@{{ detalle.timestamp_llegada }}</td>
                                    <td>@{{ detalle.material }}</td>
                                    <td style="text-align: right">
                                        <span v-if="conciliacion.estado == 0">
                                            <a href="#" @click="cambiar_cubicacion(detalle)" style="text-decoration: underline">@{{ detalle.cubicacion_camion }} m<sup>3</sup></a>
                                        </span>
                                        <span v-else>
                                            @{{ detalle.cubicacion_camion }} m<sup>3</sup>
                                        </span>
                                    </td>
                                    <td style="text-align: right">$ @{{ detalle.importe }}</td>
                                    @if(Auth::user()->can(['eliminar-viaje-conciliacion']))
                                        <td v-if="conciliacion.estado == 0">
                                            <button class="btn btn-xs btn-danger" @click="eliminar_detalle(detalle.idconciliacion_detalle)"><i class="fa fa-close"></i></button>
                                        </td>
                                    @endif
                                </tr>
                                <tr v-if="moviles.length">
                                    <td colspan="5">
                                        <strong>TOTAL VIAJES MÓVILES</strong>
                                    </td>
                                    <td style="text-align: right"><strong>@{{ conciliacion.volumen_viajes_moviles }} m<sup>3</sup></strong></td>
                                    <td style="text-align: right"><strong>$ @{{ conciliacion.importe_viajes_moviles }}</strong></td>
                                </tr>
                                <tr><td v-bind:colspan="conciliacion.estado == 0 ? '8' : '7'"></td> </tr>
                            <tr>
                                <td colspan="5"><strong>TOTAL</strong></td>
                                <td style="text-align: right"><strong>@{{ conciliacion.volumen }} m<sup>3</sup></strong></td>
                                <td style="text-align: right"><strong>$ @{{ conciliacion.importe }}</strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="cancelados_details" v-bind:class="(cancelados.length && !conciliados.length && !conciliacion.detalles_nc.length) ? 'active' : '' + ' fade in tab-pane table-responsive'">
                        <table class="table table-striped table-bordered small">
                            <thead>
                            <tr>
                                <th style="text-align: center">Camión</th>
                                <th style="text-align: center">Ticket (Código)</th>
                                <th style="text-align: center">Fecha y Hora de Llegada</th>
                                <th style="text-align: center">Material</th>
                                <th style="text-align: center">Cubicación</th>
                                <th style="text-align: center">Importe</th>
                                <th style="text-align: center">Fecha Cancelación</th>
                                <th style="text-align: center">Persona que Canceló</th>
                                <th style="text-align: center">Motivo de Cancelación</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="detalle in cancelados">
                                <td>@{{ detalle.camion }}</td>
                                <td>@{{ detalle.code }}</td>
                                <td>@{{ detalle.timestamp_llegada }}</td>
                                <td>@{{ detalle.material }}</td>
                                <td style="text-align: right">@{{ detalle.cubicacion_camion }} m<sup>3</sup></td>
                                <td style="text-align: right">$ @{{ detalle.importe }}</td>
                                <td>@{{ detalle.cancelacion.timestamp }}</td>
                                <td>@{{ detalle.cancelacion.cancelo }}</td>
                                <td>@{{ detalle.cancelacion.motivo }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="no_conciliados_details" v-bind:class="(conciliacion.detalles_nc.length && !cancelados.length && !conciliados.length) ? 'active' : '' + ' fade in tab-pane table-responsive'">
                        <table class="table table-striped table-bordered small">
                            <thead>
                            <tr>
                                <th style="text-align: center">Ticket (Código)</th>
                                <th style="text-align: center">Registro Intento</th>
                                <th style="text-align: center; width: 130px">Fecha y Hora Intento Conciliación </th>
                                <th style="text-align: center">Motivo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="detalle in conciliacion.detalles_nc">
                                <td>@{{ detalle.Code }}</td>
                                <td>@{{ detalle.registro }}</td>
                                <td>@{{ detalle.timestamp }}</td>
                                <td>@{{ detalle.detalle }}</td>
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

            <!-- Modal Editar Fecha y Folio -->
            <div class="modal fade" id="detalles_conciliacion" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">EDITAR DETALLES DE LA CONCILIACIÓN</h4>
                  </div>
                  <div class="modal-body">
                      {!! Form::open(['route' => ['conciliaciones.update', $conciliacion->idconciliacion], 'method' => 'patch', 'class' => 'form_update']) !!}
                      <input type="hidden" value="detalles" name="action">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Fecha:</label>
                                  <input type="text" class="form-control input-sm" name="fecha" v-bind:value="conciliacion.fecha" v-datepickerconciliacion>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Folio:</label>
                                  <input type="number" step="0" class="form-control input-sm" name="folio" v-bind:value="conciliacion.folio">
                              </div>
                          </div>
                      </div>
                      {!! Form::close() !!}
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="modificar_detalles">Guardar Cambios</button>
                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            </span>
        </section>
    </conciliaciones-edit>
</div>

@stop
