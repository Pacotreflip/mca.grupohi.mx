<h1>VIAJES</h1>
{!! Breadcrumbs::render('viajes_netos.carga_manual') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-manual inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            {!! Form::open(['route' => ['viajes_netos.manual.store'], 'class' => 'form_carga_manual']) !!}
            <h3>INGRESAR VIAJES</h3>
            <div v-if="!cargando" class="table-responsive">
                <table class="table table-hover table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Fecha Llegada</th>
                            <th>Hora Llegada</th>
                            <th>Camión</th>
                            <th>Cubicación</th>
                            <th>Origen</th>
                            <th>Tiro</th>
                            <th>Material</th>
                            <th><button class="btn btn-xs btn-primary" @click="addViaje"><i class="fa fa-plus-circle"></i></button></th>
                        </tr>
                    </thead>
                    <tbody v-for="(viaje, index) in form.viajes">
                        <tr>
                            <td>@{{ index + 1 }}</td>
                            <td>
                                <input v-bind:name="'viajes[' + (index + 1) + '][Codigo]'" type="text" class="form-control input-sm" v-model="viaje.Codigo">
                            </td>
                            <td>
                                <input v-bind:name="'viajes[' + (index + 1) + '][FechaLlegada]'" type="text" v-bind:class="'form-control input-sm FechaLlegada' + index" v-datepicker>
                            </td>
                            <td>
                                <input v-bind:name="'viajes[' + (index + 1) + '][HoraLlegada]'" type="time" class="form-control input-sm" v-model="viaje.HoraLlegada">
                            </td>
                            <td>
                                <select v-bind:name="'viajes[' + (index + 1) + '][IdCamion]'" class="form-control input-sm" v-on:change="setCamion(viaje)" v-model="viaje.IdCamion">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($camiones as $key => $camion)
                                    <option value="{{ $key }}">{{ $camion }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input v-bind:name="'viajes[' + (index + 1) + '][Cubicacion]'" type="number" step="any" class="form-control input-sm" v-model="viaje.Cubicacion">
                            </td>
                            <td>
                                <select v-bind:name="'viajes[' + (index + 1) + '][IdOrigen]'" class="form-control input-sm" v-on:change="setTiros(viaje)" v-model="viaje.IdOrigen">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($origenes as $key => $origen)
                                    <option value="{{ $key }}">{{ $origen }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select v-bind:name="'viajes[' + (index + 1) + '][IdTiro]'" class="form-control input-sm" v-model="viaje.IdTiro">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="(tiro, key) in viaje.Tiros" v-bind:value="key">@{{ tiro }}</option>
                                </select>
                            </td>
                            <td>
                                <select v-bind:name="'viajes[' + (index + 1) + '][IdMaterial]'" class="form-control input-sm" v-model="viaje.IdMaterial">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($materiales as $key => $material)
                                    <option value="{{ $key }}">{{ $material }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-xs btn-danger" @click="removeViaje(form.viajes.indexOf(viaje), $event)"><i class="fa fa-minus-circle"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Motivo</strong></td>
                            <td colspan="9">
                                <input v-bind:name="'viajes[' + (index + 1) + '][Motivo]'" type="text" class="form-control input-sm" v-model="viaje.Motivo">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-show="form.viajes.length" class="form-group col-md-12" style="text-align: center; margin-top: 20px">
                <button class="btn btn-success btn-sm" type="submit" @click="confirmarRegistro">
                    <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i> Guardar</span>
                    <span v-else><i class="fa fa-save"></i> Guardar</span>
                </button>
            </div>
            {!! Form::close() !!}
        </section>
    </viajes-manual-registro>
</div>