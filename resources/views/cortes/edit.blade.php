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
                    <p class="small">La información en color <strong style="color: red">rojo</strong> señala los cambios proipuestos por el checador.</p>
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
                                <th style="text-align: center"> Observaciones de Modificaicón</th>
                                <th style="text-align: center"> Modificar Viaje </th>
                                <th style="text-align: center"> Descartar Cambios </th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(viaje, index) in corte.viajes_netos">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ viaje.tipo }}</td>
                                    <td>@{{ viaje.camion }}</td>
                                    <td>@{{ viaje.codigo }}</td>
                                    <td>@{{ viaje.timestamp_llegada }}</td>
                                    <td style="color: red" v-if="viaje.origen_nuevo"> <strong>@{{ viaje.origen_nuevo }}</strong> </td>
                                    <td v-else>@{{ viaje.origen }}</td>
                                    <td>@{{ viaje.tiro }}</td>
                                    <td style="color: red" v-if="viaje.material_nuevo"><strong>@{{ viaje.material_nuevo }}</strong></td>
                                    <td v-else>@{{ viaje.material }}</td>
                                    <td v-if="viaje.cubicacion_nueva" style="text-align: right; color: red"><strong>@{{ viaje.cubicacion_nueva }} m<sup>3</sup></strong></td>
                                    <td v-else style="text-align: right">@{{ viaje.cubicacion }} m<sup>3</sup></td>
                                    <td v-if="viaje.importe_nuevo" style="text-align: right; color: red"><strong>$@{{ formato(viaje.importe_nuevo) }}</strong></td>
                                    <td v-else style="text-align: right">$@{{ formato(viaje.importe) }}</td>
                                    <td>@{{ viaje.registro_primer_toque }}</td>
                                    <td>@{{ viaje.registro }}</td>
                                    <td>@{{ viaje.observaciones }}</td>
                                    <td>
                                        <button class="btn btn-xs btn-info" @click="editar(viaje)"><i class="fa fa-edit"></i></button>
                                    </td>
                                    <td>
                                        <button v-if="viaje.modified" class="btn btn-xs btn-danger" @click="descartar(viaje)"><i class="fa fa-undo"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </span>
            </section>

            <!-- Modal de Modificación-->
            <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button @click="doSomethingOnHidden" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">MODIFICAR VIAJE</h4>
                        </div>
                        {!! Form::open(['id' => 'form_modificar']) !!}
                        <div class="modal-body">
                            <app-errors v-bind:form="form"></app-errors>
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
                                        <label for="origen">ORIGEN</label>
                                        <select name="origen" class="form-control" v-model="form.data.id_origen">
                                            <option value>-- SELECCIONE --</option>
                                            @foreach($origenes as $key => $origen)
                                                <option value="{{$key}}">{{$origen}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cubicacion">CUBICACIÓN</label>
                                        <input name="cubicacion" type="number" step="any" class="form-control" v-model="form.data.cubicacion">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cubicacion">OBSERVACIONES</label>
                                        <input type="text" name="observaciones" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="doSomethingOnHidden" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" @click="confirmar_modificacion" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </corte-edit>
</div>
@endsection