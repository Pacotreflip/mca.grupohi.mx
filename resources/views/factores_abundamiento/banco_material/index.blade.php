@extends('layout')

@section('content')
<h1>FACTOR DE ABUNDAMIENTO</h1>
{!! Breadcrumbs::render('factores_abundamiento_banco_material.index') !!}
<hr>
<div id="app">
    <fda-bancomaterial></fda-bancomaterial>
</div>
@stop