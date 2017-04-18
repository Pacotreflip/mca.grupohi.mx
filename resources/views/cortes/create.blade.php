@extends('layout')

@section('content')
<h1>CORTE DE CHECADOR</h1>
{!! Breadcrumbs::render('corte.create') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <corte-create inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <h3>BUSCAR VIAJES</h3>
            {!! Form::open(['id' => 'form_buscar']) !!}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>FECHA INICIAL (*)</label>
                        <input type="text" name="fecha_inicial" class="form-control" v-datepicker>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>HORA INICIAL (*)</label>
                        <input type="text" name="hora_inicial" class="time start form-control"  v-timepicker>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>FECHA FINAL (*)</label>
                        <input type="text" name="fecha_final" class="form-control" v-datepicker>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>HORA FINAL (*)</label>
                        <input type="text" name="hora_final" class="time end form-control"  v-timepicker>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="buscar">
                    <span v-if="cargando"><i class="fa fa-spinner fa-spin"></i> BUSCAR</span>
                    <span v-else><i class="fa fa-search"></i> BUSCAR</span>
                </button>
                <button v-if="viajes_netos.length" class="btn btn-success pull-right" @click="confirmar_corte">
                    <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i> GUARDAR CORTE</span>
                    <span v-else><i class="fa fa-save"></i> GUARDAR CORTE</span>
                </button>
            </div>
            <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
            {!! Form::close() !!}

            <!-- Tabla de Resultados-->
            <section v-if="viajes_netos.length">
                <hr>
                <h3>RESULTADOS DE LA BÚSQUEDA</h3>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered small">
                        <thead>
                            <tr>
                                <th style="text-align: center"> # </th>
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
                                <th style="text-align: center"> Autorizó </th>
                                <th style="text-align: center"> Validó </th>
                                <th style="text-align: center"> Estado </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(viaje_neto, index) in viajes_netos">
                                <td>@{{ index + 1 }}</td>
                                <td>@{{ viaje_neto.camion }}</td>
                                <td>@{{ viaje_neto.codigo }}</td>
                                <td>@{{ viaje_neto.timestamp_llegada }}</td>
                                <td>@{{ viaje_neto.origen }}</td>
                                <td>@{{ viaje_neto.tiro }}</td>
                                <td>@{{ viaje_neto.material }}</td>
                                <td style="text-align: right">@{{ viaje_neto.cubicacion }} m<sup>3</sup></td>
                                <td style="text-align: right">$@{{ formato(viaje_neto.importe) }}</td>
                                <td>@{{ viaje_neto.registro_primer_toque }}</td>
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
    </corte-create>
</div>
@endsection