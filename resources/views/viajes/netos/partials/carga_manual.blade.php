<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.netos.carga_manual') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-manual inline-template>
        <section>            
            <app-errors v-bind:form="form"></app-errors>
            <div v-if="!cargando" class="table-responsive col-md-10 col-md-offset-1">
                <table class="table table-hover table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Camión</th>
                            <th>Origen</th>
                            <th>Tiro</th>
                            <th>Material</th>
                            <th><button class="btn btn-xs btn-primary" @click="addViaje"><i class="fa fa-plus-circle"></i></button></th>
                        </tr>
                    </thead>
                    <tbody v-for="viaje in form.viajes">
                        <tr>
                            <td>@{{ viaje.Id }}</td>
                            <td>
                                <input id="FechaLlegada" @blur="setFechaLlegada(viaje, $event)" v-datepicker type="text" class="form-control fecha input-sm" v-model="viaje.FechaLlegada">
                            </td>
                            <td>
                                <input type="time" class="form-control input-sm" v-model="viaje.HoraLlegada">
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdCamion">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($camiones as $camion)
                                    <option value="{{ $camion->IdCamion}}">{{ $camion->Economico }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdOrigen" v-on:change="setTiros(viaje)">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($origenes as $origen)
                                    <option value="{{ $origen->IdOrigen }}">{{ $origen->Descripcion }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdTiro">
                                    <option value>--SELECCIONE--</option>
                                    <option v-for="tiro in viaje.Tiros" v-bind:value="tiro.IdTiro">@{{ tiro.Descripcion }}</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm" v-model="viaje.IdMaterial">
                                    <option value>--SELECCIONE--</option>
                                    @foreach($materiales as $material)
                                    <option value="{{ $material->IdMaterial }}">{{ $material->Descripcion }}</option>
                                    @endforeach
                                </select>
                            </td> 
                            <td>
                                <button class="btn btn-xs btn-danger" @click="removeViaje(viaje)"><i class="fa fa-minus-circle"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Observaciones</strong></td>
                            <td colspan="6">
                                <input type="text" class="form-control input-sm" v-model="viaje.Observaciones">
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
        </section>
    </viajes-manual-registro>
</div>