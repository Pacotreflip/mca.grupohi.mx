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
                <button type="submit" class="btn btn-primary" @click="buscar"><i class="fa fa-search"></i> BUSCAR</button>
                <button v-if="viajes_netos.length" class="btn btn-success pull-right" @click="confirmar_corte"><i class="fa fa-save"></i> GUARDAR CORTE</button>
            </div>
            <!-- Tabla de Resultaros-->
            <section v-if="viajes_netos.length">
                <h3>RESULTADOS DE LA BÚSQUEDA</h3>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered small">
                        <thead>
                            <tr>
                                <td>#</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(viaje_neto, index) in viajes_netos">
                                <td>@{{ index + 1 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </corte-create>
</div>
@endsection