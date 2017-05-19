@extends('layout_width')

@section('content')
    <h1>VIAJES @if($action == 'en_conflicto')EN CONFLICTO @endif</h1>
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

                @if($action == 'en_conflicto')
                <div class="form-group">

                {!! Form::open(['class' => 'form_buscar_en_conflicto']) !!}
                <h4><label style="cursor: pointer"><input type="radio" name="tipo_busqueda" value="fecha" checked="checked">BUSCAR POR FECHA</label></h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>FECHA INICIAL</label>
                            <input type="text" name="FechaInicial" class="form-control" v-datepicker>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>FECHA FINAL </label>
                            <input type="text" name="FechaFinal" class="form-control" v-datepicker>
                        </div>
                    </div>
                </div>
                <h4><label style="cursor: pointer"><input type="radio" name="tipo_busqueda" value="codigo" > BUSCAR POR CÓDIGO</label></h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ticket (Código)</label>
                            <input type="text" name="Codigo" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit" @click="buscar_en_conflicto">
                        <span v-if="cargando"><i class="fa fa-spinner fa-spin"></i> Buscando</span>
                        <span v-else><i class="fa fa-search"></i> Buscar</span>
                    </button>
                    <button class="btn  btn-info" @click="pdf_conflicto"><i class="fa fa-file-pdf-o"></i> VER PDF</button>
                </div>

                {!! Form::close() !!}
                </div>
                @else
                <h3>BUSCAR VIAJES</h3>
                {!! Form::open(['class' => 'form_buscar']) !!}
                <input type="hidden" name="type" value>
                <h4><label style="cursor: pointer"><input type="radio" name="tipo_busqueda" value="fecha" checked="checked">BUSCAR POR FECHA</label></h4>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ESTADO DE LOS VIAJES (*)</label>
                            <select name="Estado" class="form-control" v-model="form.estado">
                                <option value>-- SELECCIONE -- </option>
                                <option value="T">Todos</option>
                                <option value="C">Conciliados</option>
                                <option value="NC">No Conciliados</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>TIPO DE VIAJES (*)</label>
                            <span v-if="form.estado == 'C'">
                            <select id="tipo" name="Tipo[]" class="form-control" multiple="multiple" v-select2 :disabled="!form.estado">
                                <optgroup label="CARGADOS MANUALMENTE">
                                    <option value="CM_V">Manuales - Validados</option>
                                </optgroup>
                                <optgroup label="CARGADOS DESDE APLICACIÓN MÓVIL">
                                    <option value="M_V">Móviles - Validados</option>
                                </optgroup>
                            </select>
                            </span>
                            <span v-else>
                            <select id="tipo" name="Tipo[]" class="form-control" multiple="multiple" v-select2 :disabled="!form.estado">
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
                            </span>
                        </div>
                    </div>

                </div>
                    <h4><label style="cursor: pointer"><input type="radio" name="tipo_busqueda" value="codigo" > BUSCAR POR CÓDIGO</label></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ticket (Código)</label>
                                <input type="text" name="Codigo" class="form-control">
                            </div>
                        </div>

                    </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit" @click="buscar">
                        <span v-if="cargando"><i class="fa fa-spinner fa-spin"></i> Buscando</span>
                        <span v-else><i class="fa fa-search"></i> Buscar</span>
                    </button>
                    <button class="btn  btn-info" @click="pdf"><i class="fa fa-file-pdf-o"></i> VER PDF</button>
                    <button class="btn  btn-success" @click="excel"><i class="fa fa-file-excel-o"></i> EXCEL</button>
                </div>
                <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
                {!! Form::close() !!}
                @endif

                
                <hr>
                
                <section v-if="viajes_netos.length" id="results">
                    <h3>RESULTADOS DE LA BÚSQUEDA</h3>
                    
                    <div class="table-responsive pre-scrollable">
                        (P) Viaje en conflicto de tiempo pero con aprobación de pago registrada.
                        <table class="table  table-striped table-bordered small">
                            <thead>
                            <tr style="background-color: #f9f9f9">
                                <th colspan="5" style="text-align: center">INFORMACIÓN GENERAL DEL VIAJE</th>
                                <th colspan="4" style="text-align: center">DETALLES DEL VIAJE</th>
                                <th colspan="2" style="text-align: center">CAMIÓN</th>
                                <th colspan="3" style="text-align: center">EMPRESA</th>
                                <th colspan="3" style="text-align: center">SINDICATO</th>
                                <th colspan="2" style="text-align: center">CONCILIACIÓN</th>
                                <th colspan="5" style="text-align: center">ESTADO DEL VIAJE</th>
                            </tr>
                            <tr>
                                <th style="text-align: center"> # </th>
                                <th style="text-align: center"> TIPO </th>
                                <th style="text-align: center"> FECHA LLEGADA </th>
                                <th style="text-align: center"> HORA LLEGADA</th>
                                <th style="text-align: center"> TICKET - CÓDIGO </th>

                                <th style="text-align: center"> ORIGEN</th>
                                <th style="text-align: center"> TIRO </th>
                                <th style="text-align: center"> MATERIAL </th>
                                <th style="text-align: center"> IMPORTE </th>

                                <th style="text-align: center"> ECONÓMICO </th>
                                <th style="text-align: center"> CUBICACIÓN	</th>

                                <th style="text-align: center"> CAMIÓN </th>
                                <th style="text-align: center"> VIAJE NETO </th>
                                <th style="text-align: center"> VIAJE </th>
                                <th style="text-align: center"> CAMIÓN </th>
                                <th style="text-align: center"> VIAJE NETO </th>
                                <th style="text-align: center"> VIAJE </th>

                                <th style="text-align: center"> CONCILIÓ </th>
                                <th style="text-align: center"> ID CONCILIACIÓN </th>

                                <th style="text-align: center"> REGISTRÓ </th>
                                <th style="text-align: center"> AUTORIZÓ </th>
                                <th style="text-align: center"> VALIDÓ </th>
                                <th style="text-align: center"> ESTADO </th>
                                <th style="text-align: center"> CONFLICTO </th>
                            </tr>

                            </thead>
                            <tbody>
                            <tr v-for="(viaje_neto, index) in viajes_netos">

                                <td style="white-space: nowrap">@{{ index + 1 }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.tipo }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.FechaLlegada }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.HoraLlegada }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.Code }}</td>

                                <td style="white-space: nowrap">@{{ viaje_neto.origen }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.tiro }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.material }}</td>
                                <td style="white-space: nowrap; text-align: right">$ @{{ formato(viaje_neto.importe) }}</td>

                                <td style="white-space: nowrap">@{{ viaje_neto.camion }}</td>
                                <td style="white-space: nowrap; text-align: right">@{{ viaje_neto.cubicacion }}</td>

                                <td style="white-space: nowrap">@{{ viaje_neto.empresa_camion }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.empresa_viajeneto }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.empresa_viaje }}</td>

                                <td style="white-space: nowrap">@{{ viaje_neto.sindicato_camion }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.sindicato_viajeneto }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.sindicato_viaje }}</td>

                                <td style="white-space: nowrap">@{{ viaje_neto.concilio }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.id_conciliacion }}</td>

                                <td style="white-space: nowrap">@{{ viaje_neto.registro }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.autorizo }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.valido }}</td>
                                <td style="white-space: nowrap">@{{ viaje_neto.estado }}</td>
                                <td style="white-space: nowrap" v-if ="viaje_neto.conflicto>0&&!viaje_neto.conflicto_pagable>0">
                                    <a style="cursor: pointer" @click="detalle_conflicto(viaje_neto.conflicto, viaje_neto.id)" >Ver</a></td>
                                <td style="white-space: nowrap" v-if ="viaje_neto.conflicto>0&&viaje_neto.conflicto_pagable>0">
                                    
                                    <a style="cursor: pointer" @click="detalle_conflicto_pagable(viaje_neto.conflicto, viaje_neto.id)" >(P) Ver</a></td>
                                <td style="white-space: nowrap" v-if ="!viaje_neto.conflicto>0">N/A</td>
                            </tr>
                            </tbody>
                        </table>
                        (P) Viaje en conflicto de tiempo pero con aprobación de pago registrada.
                        <!-- Modal Editar Fecha y Folio -->
                        <div class="modal fade" id="detalles_conflicto" tabindex="-1" role="dialog">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">DETALLES DEL CONFLICTO</h4>
                              </div>
                                <div class="modal-body"  >
                                  {!! Form::open(['route' => ['viajes_netos.update'], 'method' => 'patch', 'class' => 'form_pagable']) !!}
                                  <input type="hidden" value="poner_pagable" name="type">
                                  
                                  <div class="row">
                                      <div class="col-md-12">
                                      <table class="table">
                                        <thead>
                                      <tr >
                                          <td style="text-align: center">
                                               Código (Ticket)
                                          </td>
                                          <td style="text-align: center">
                                               Fecha Registro
                                          </td>
                                         <td style="text-align: center">
                                               Fecha Salida
                                          </td>
                                          <td style="text-align: center">
                                               Fecha Llegada
                                          </td>
                                      </tr>
                                        </thead>
                                        <tbody>
                                            <tr  v-for="detalle_conflicto in conflicto.conflictos" v-bind:style="(detalle_conflicto.id==viaje_neto_seleccionado)?'color:#1c94c4':''" v-bind:class="(detalle_conflicto.id==viaje_neto_seleccionado)?'active':''">
                                          <td >
                                              @{{detalle_conflicto.code}}
                                          </td>
                                          <td>
                                              @{{detalle_conflicto.fecha_registro}}
                                          </td>
                                          <td>
                                              @{{detalle_conflicto.fecha_salida}}
                                          </td>
                                          <td>
                                              @{{detalle_conflicto.fecha_llegada}}
                                          </td>
                                      </tr>
                                        </tbody>
                                  </table>
                                      </div>
                                  </div>
                                  <div class="row" ><div class="col-md-12"><textarea name="motivo" class="form-control" placeholder="Ingrese el motivo para aprobar el pago."></textarea></div></div>
                                  {!! Form::close() !!}
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                @if (Auth::user()->can(['poner-viajes-conflicto-pagables']))
                                <button type="button" class="btn btn-success" @click="confirmarPonerPagable">Es Pagable</button>
                                @endif
                              </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        
                        
                        <!-- Modal Editar Fecha y Folio -->
                        <div class="modal fade" id="detalles_conflicto_pagable" tabindex="-1" role="dialog">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">DETALLES DEL CONFLICTO</h4>
                              </div>
                                <div class="modal-body"  >
                                  {!! Form::open(['route' => ['viajes_netos.update'], 'method' => 'patch', 'class' => 'form_pagable']) !!}
                                  <input type="hidden" value="poner_pagable" name="type">
                                  
                                  <div class="row">
                                      <div class="col-md-12">
                                      <table class="table">
                                        <thead>
                                      <tr >
                                          <td style="text-align: center">
                                               Código (Ticket)
                                          </td>
                                          <td style="text-align: center">
                                               Fecha Registro
                                          </td>
                                         <td style="text-align: center">
                                               Fecha Salida
                                          </td>
                                          <td style="text-align: center">
                                               Fecha Llegada
                                          </td>
                                      </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="detalle_conflicto in conflicto.conflictos" v-bind:style="(detalle_conflicto.id==viaje_neto_seleccionado)?'color:#1c94c4':''" v-bind:class="(detalle_conflicto.id==viaje_neto_seleccionado)?'active':''">
                                          <td >
                                              @{{detalle_conflicto.code}}
                                          </td>
                                          <td>
                                              @{{detalle_conflicto.fecha_registro}}
                                          </td>
                                          <td>
                                              @{{detalle_conflicto.fecha_salida}}
                                          </td>
                                          <td>
                                              @{{detalle_conflicto.fecha_llegada}}
                                          </td>
                                      </tr>
                                        </tbody>
                                  </table>
                                      </div>
                                  </div>
                                  <hr>
                                  <div class="row" ><div class="col-md-3"><label>Aprobó pago:</label></div><div class="col-md-9">@{{conflicto.aprobo_pago}}</div></div>
                                  <div class="row">
                                      <div class="col-md-3"><label>Motivo:</label></div><div class="col-md-9">@{{conflicto.motivo}}</div>
                                      </div>
                                  {!! Form::close() !!}
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                              </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        
                    </div>
                </section>
            </section>
            
        </viajes-index>
    </div>
@stop
