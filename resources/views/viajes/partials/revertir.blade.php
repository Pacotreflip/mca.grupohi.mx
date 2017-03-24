<h1>VIAJES</h1>
{!! Breadcrumbs::render('viajes.revertir') !!}
<hr>
<div id="app">
    <global-errors></global-errors>
    <viajes-validar inline-template>
        <section>
            <app-errors v-bind:form="form"></app-errors>