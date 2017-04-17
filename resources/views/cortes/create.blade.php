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
        </section>
    </corte-create>
</div>
@endsection