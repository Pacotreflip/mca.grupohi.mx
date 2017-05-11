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
            {!! Form::open(['class' => 'form_conciliacion_create']) !!}
                <!-- Fecha, Folio, Empresa, Sindicato -->
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Empresa">Empresa: </label>
                            {!! Form::select('idempresa', $empresas, null, ['class' => 'form-control','placeholder' => '--SELECCIONE--']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Empresa">Sindicato: </label>
                            {!! Form::select('idsindicato', $sindicatos, null, ['class' => 'form-control','placeholder' => '--SELECCIONE--']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha">Fecha de Conciliación: </label>
                            <input type="text" name="fecha" class="form-control" v-datepicker>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="folio">Folio: </label>
                            <input type="text" name="folio" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="importe_pagado">Importe Pagado*: </label>
                            <input type="text" name="importe_pagado" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="volumen_pagado">Volumen Pagado*: </label>
                            <input type="text" name="volumen_pagado" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        * Estos campos sólo aplican para las conciliaciones anteriores al 9 de abril de 2017
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