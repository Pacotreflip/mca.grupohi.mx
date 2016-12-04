@extends('layout')

@section('content')
<h2>{{ strtoupper(trans('strings.tarifas_tiporuta')) }}</h2>
<hr>
{!! Breadcrumbs::render('tarifas_tiporuta.index') !!}
@stop