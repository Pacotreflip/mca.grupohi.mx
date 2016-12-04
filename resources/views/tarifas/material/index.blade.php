@extends('layout')

@section('content')
<h2>{{ strtoupper(trans('strings.tarifas_material')) }}</h2>
<hr>
{!! Breadcrumbs::render('tarifas_material.index') !!}
@stop
