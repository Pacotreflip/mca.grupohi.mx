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
            {!! Form::open(['route' => 'conciliaciones.store', 'id' => 'create_conciliacion']) !!}
                <!-- Empresa y Sindicato -->
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
                    <div class="col-md-12">
                        {!! Form::submit('Registrar', ['class' => 'btn btn-success pull-right']) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </section>
    </conciliaciones-create>
@stop