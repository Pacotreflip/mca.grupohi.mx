@extends('layout')

@section('content')
<h2>{{ strtoupper(trans('strings.tarifas_peso')) }}</h2>
<hr>
{!! Breadcrumbs::render('tarifas_peso.index') !!}
@stop
