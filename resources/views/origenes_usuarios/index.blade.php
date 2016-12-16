@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.origenes_usuarios')) }}</h1>
{!! Breadcrumbs::render('origenes_usuarios.index') !!}
<hr>
<div id="app">
<origenes-usuarios></origenes-usuarios>
</div>
@stop