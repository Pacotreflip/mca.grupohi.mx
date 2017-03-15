@extends('layout')

@section('content')
<h1>CONCILIACIONES</h1>
{!! Breadcrumbs::render('conciliaciones.create') !!}
<hr>
@include('partials.errors')
<div id="app">
    <global-errors></global-errors>
    <conciliaciones-create inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>
            {!! Form::open() !!}
                <!-- Empresa y Sindicato -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Empresa">Empresa: </label>
                            {!! Form::select('idempresa', $empresas, null, ['v-model' => 'conciliacion.idempresa', 'class' => 'form-control','placeholder' => '--SELECCIONE--']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Empresa">Sindicato: </label>
                            {!! Form::select('idsindicato', $sindicatos, null, ['v-model' => 'conciliacion.idsindicato', 'class' => 'form-control','placeholder' => '--SELECCIONE--']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" value="Registrar" class="btn btn-success pull-right" @click="confirmarRegistro">
                    </div>
                </div>
            {!! Form::close() !!}
        </section>
    </conciliaciones-create>
@stop