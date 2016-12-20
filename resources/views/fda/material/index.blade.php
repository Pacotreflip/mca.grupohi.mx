@extends('layout')

@section('content')
<h1>FACTOR DE ABUNDAMIENTO</h1>
{!! Breadcrumbs::render('fda_material.index') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <fda-material inline-template>
        <div class="table-responsive col-md-6 col-md-offset-3">
            <app-errors v-bind:form="form"></app-errors>
            <div class="form-horizontal rcorners">
                <div class="form-group">
                    <label class="control-label col-sm-2">Material</label>
                    <div class="col-sm-5">
                        <select class="form-control"  v-model="factor.IdMaterial">
                            <option value >--SELECCIONE--</option>
                            <option v-for="material in materiales" v-bind:value="material.IdMaterial">
                                @{{ material.Descripcion }}
                            </option>
                        </select>            
                    </div>
                    <label class="control-label col-sm-2">FDA</label>
                    <div class="col-sm-3">
                        <input class="form-control" step="any" type="number" v-model="factor.FactorAbundamiento">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
                <button class="btn btn-success" type="submit" @click="guardar">
                    <span v-if="guardando"><i class="fa fa-spinner fa-spin"></i> Guardar</span>
                    <span v-else><i class="fa fa-save"></i> Guardar</span>
                </button>
            </div>      
            <hr>
            <table v-if="factores.length" class="table table-hover">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>FDA</th>
                        <th>Guardar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="factor in factores">
                        <td>@{{ factor.material.Descripcion }}</td>
                        <td>
                            <input class="input-sm form-control" v-model="factor.FactorAbundamiento" type="number" step="any">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success" type="submit" @click="actualizar(factor)">
                                <span v-if="factor.guardando"><i class="fa fa-spinner fa-spin"></i></span>
                                <span v-else><i class="fa fa-save"></i></span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>     
    </fda-material>
</div>
@stop