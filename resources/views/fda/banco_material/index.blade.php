@extends('layout')

@section('content')
<h1>FACTOR DE ABUNDAMIENTO
    <a href="{{ route('csv.fda-banco-material') }}" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('fda_banco_material.index') !!}
<hr>
@include('partials.errors')
<div id="app">
    <global-errors></global-errors>
    <fda-bancomaterial inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            <div class="form-horizontal rcorners ">
                <div class="form-group">
                    <legend class="scheduler-border text-center"><small><i class="fa fa-cubes"></i> NUEVO FACTOR DE ABUNDAMIENTO</small></legend>
                    <label class="control-label col-sm-1">Banco</label>
                    <div class="col-sm-3">
                        <select class="form-control input-sm"  v-model="factor.IdBanco">
                            <option value >--SELECCIONE--</option>
                            <option v-for="banco in bancos" v-bind:value="banco.IdOrigen">
                                @{{ banco.Descripcion }}
                            </option>
                        </select>            
                    </div>
                    <label class="control-label col-sm-1">Material</label>
                    <div class="col-sm-3">
                        <select class="form-control input-sm"  v-model="factor.IdMaterial">
                            <option value >--SELECCIONE--</option>
                            <option v-for="material in materiales" v-bind:value="material.IdMaterial">
                                @{{ material.Descripcion }}
                            </option>
                        </select>            
                    </div>
                    <label class="control-label col-sm-1">FDA</label>
                    <div class="col-sm-3">
                        <input class="form-control input-sm" step="any" type="number" v-model="factor.FactorAbundamiento">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
                <button class="btn btn-success" type="submit" @click="guardar">
                    <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i> Guardar</span>
                    <span v-else><i class="fa fa-save"></i> Guardar</span>
                </button>
            </div>
            <div v-if="factores.length" class="table-responsive rcorners">
                <legend class="scheduler-border text-center"><small><i class="fa fa-list"></i> FACTORES DE ABUNDAMIENTO</small></legend>
                <table v-if="factores.length" class="table table-hover small">
                    <thead>
                        <tr>
                            <th>Banco</th>
                            <th>Material</th>
                            <th>FDA</th>
                            <th>Actulizar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="fda in factores">
                            <td><small>@{{ fda.banco }}</small></td>
                            <td><small>@{{ fda.material }}</small></td>
                            <td>
                                <input style="width: 100px" type="number" step="any" class="form-control input-sm" v-model="fda.FactorAbundamiento"></td>
                            <td>
                                <button class="btn btn-primary btn-xs" type="submit" @click="actualizar(fda)">
                                    <span v-if="fda.guardando"><i class="fa fa-spinner fa-spin"></i></span>
                                    <span v-else><i class="fa fa-save"></i></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </fda-bancomaterial>
</div>
@stop