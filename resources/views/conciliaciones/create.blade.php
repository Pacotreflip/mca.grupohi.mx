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
            <form>
                <!-- Empresa y Sindicato -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Empresa">Empresa: </label>
                            {!! Form::select('empresa', $empresas, null, ['class' => 'form-control','placeholder' => '--SELECCIONE--']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Empresa">Sindicato: </label>
                            {!! Form::select('empresa', $sindicatos, null, ['class' => 'form-control','placeholder' => '--SELECCIONE--']) !!}
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </conciliaciones-create>
@stop