@extends('layout')

@section('content')
@include('partials.errors')
<div id="app">
    <global-errors></global-errors>
    <corte-edit inline-template>
        <section>
            <h1>CORTE {{ $corte->id }} <small>({{ $corte->estado }})</small>
                @if($corte->estatus == 1)
                    <button v-bind:disabled="guardando" class="btn btn-success btn-sm pull-right" @click="confirmar_cierre">
                        <span v-if="guardando">
                            <i class="fa fa-spinner fa-spin"></i> CERRANDO
                        </span>
                        <span v-else>
                            <i class="fa fa-check"></i> CERRAR / TERMINAR
                        </span>
                    </button>
                @endif
            </h1>
            {!! Breadcrumbs::render('corte.edit', $corte) !!}
            <hr>
            <input type="hidden" id="id_corte" value="{{$corte->id}}">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel panel-heading">
                            DETALLES DEL CORTE
                        </div>
                        <div class="panel-body">
                            <strong>CHECADOR: </strong> {{ $corte->checador->present()->nombreCompleto }}<br>
                            <strong>FECHA y HORA DEL CORTE: </strong> {{ $corte->timestamp->format('d-M-Y h:i:s a') }} <small>({{$corte->timestamp->diffForHumans()}})</small> <br>
                            <strong>NÚMERO DE VIAJES: </strong> {{$corte->corte_detalles->count() }}
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <section id="tabla_viajes">
                <span v-if="cargando">
                    <div class="text-center">
                        <big><i class="fa fa-spinner fa-spin"></i> CARGANDO VIAJES </big>
                    </div>
                </span>
                <span v-else-if="corte.viajes_netos.length">
                    <h3>VIAJES DEL CORTE</h3>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::open() !!}
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar Código.." v-model="form.busqueda">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" @click="buscar">BUSCAR</button>
                                </span>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <br>
                    <app-errors v-bind:form="form"></app-errors>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered small">
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
                                <th style="text-align: center"> Checador Primer Toque </th>
                                <th style="text-align: center"> Checador Segundo Toque </th>
                                <th style="text-align: center"> Confirmar </th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(viaje, index) in corte.viajes_netos">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ viaje.tipo }}</td>
                                    <td>@{{ viaje.camion }}</td>
                                    <td>@{{ viaje.codigo }}</td>
                                    <td>@{{ viaje.timestamp_llegada }}</td>
                                    <td>@{{ viaje.origen }}</td>
                                    <td>@{{ viaje.tiro }}</td>
                                    <td>@{{ viaje.material }}</td>
                                    <td style="text-align: right">@{{ viaje.cubicacion }} m<sup>3</sup></td>
                                    <td style="text-align: right">$@{{ formato(viaje.importe) }}</td>
                                    <td>@{{ viaje.registro_primer_toque }}</td>
                                    <td>@{{ viaje.registro }}</td>
                                    <td>
                                        <i v-if="viaje.confirmed" style="color: green" class="fa fa-check-circle fa-lg" @click="informacion(viaje)"></i>
                                        <i v-else style="color: red" class="fa fa-exclamation-circle fa-lg" @click="informacion(viaje)"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </span>
            </section>

            <!-- Modal de Confirmación-->
            <div class="modal fade" id="info_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">CONFIRMAR VIAJE</h4>
                        </div>
                        {!! Form::open(['id' => 'form_confirmar']) !!}
                        <div class="modal-body">
                            <app-errors v-bind:form="form"></app-errors>
                            <div class="row">
                                <h4 class="text-center">INFORMACIÓN DEL VIAJE</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered small">
                                        <thead>
                                        <tr>
                                            <th>CÓDIGO</th>
                                            <th>ORIGEN</th>
                                            <th>TIRO</th>
                                            <th>MATERIAL</th>
                                            <th>CUBICACIÓN</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-if="viaje">
                                            <td>@{{ viaje.codigo }}</td>
                                            <td>@{{ viaje.origen }}</td>
                                            <td>@{{ viaje.tiro }}</td>
                                            <td>@{{ viaje.material }}</td>
                                            <td>@{{ viaje.cubicacion }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <section v-if="viaje.corte_cambio" id="modificaciones_table">
                                <hr>
                                <h4 class="text-center">
                                    MODIFICACIONES
                                </h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered small">
                                        <thead>
                                        <tr>
                                            <th v-if="viaje.corte_cambio.origen_nuevo">ORIGEN NUEVO</th>
                                            <th v-if="viaje.corte_cambio.tiro_nuevo">TIRO NUEVO</th>
                                            <th v-if="viaje.corte_cambio.material_nuevo">MATERIAL NUEVO</th>
                                            <th v-if="viaje.corte_cambio.cubicacion_nueva">CUBICACIÓN NUEVA</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td v-if="viaje.corte_cambio.origen_nuevo">@{{ viaje.corte_cambio.origen_nuevo.Descripcion }}</td>
                                            <td v-if="viaje.corte_cambio.tiro_nuevo">@{{ viaje.corte_cambio.tiro_nuevo.Descripcion }}</td>
                                            <td v-if="viaje.corte_cambio.material_nuevo">@{{ viaje.corte_cambio.material_nuevo.Descripcion }}</td>
                                            <td v-if="viaje.corte_cambio.cubicacion_nueva">@{{ viaje.corte_cambio.cubicacion_nueva }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <strong>JUSTIFICACIÓN: </strong> @{{ viaje.corte_cambio.justificacion }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button class="btn btn-xs btn-danger pull-right" @click="descartar"><i class="fa fa-undo"> Revertir Modificaciones</i> </button>
                                </div>
                            </section>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" @click="editar" class="btn btn-primary">Modificar Viaje</button>
                            <button type="submit" @click="confirmar_confirmacion" class="btn btn-success">Confirmar</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <!-- Modal de Modificación-->
            <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">MODIFICAR VIAJE</h4>
                        </div>
                        {!! Form::open(['id' => 'form_modificar']) !!}
                        <div class="modal-body">
                            <app-errors v-bind:form="form"></app-errors>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="origen">ORIGEN</label>
                                        <select name="origen" class="form-control" v-model="form.data.id_origen">
                                            <option value>-- SELECCIONE --</option>
                                            @foreach($origenes as $key => $origen)
                                                <option value="{{$key}}">{{$origen}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tiro">TIRO</label>
                                        <select name="tiro" class="form-control" v-model="form.data.id_tiro">
                                            <option value>-- SELECCIONE --</option>
                                            @foreach($tiros as $key => $tiro)
                                                <option value="{{$key}}">{{$tiro}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="material">MATERIAL</label>
                                        <select name="material" class="form-control" v-model="form.data.id_material">
                                            <option value>-- SELECCIONE --</option>
                                            @foreach($materiales as $key => $material)
                                                <option value="{{$key}}">{{$material}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cubicacion">CUBICACIÓN</label>
                                        <input name="cubicacion" type="number" step="any" class="form-control" v-model="form.data.cubicacion">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="justificacion">JUSTIFICACIÓN</label>
                                        <input type="text" name="justificacion" class="form-control" v-model="form.data.justificacion">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" @click="confirmar_modificacion" class="btn btn-success">Guardar y Confirmar</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </corte-edit>
</div>
@endsection