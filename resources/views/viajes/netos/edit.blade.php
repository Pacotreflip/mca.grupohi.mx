@extends('layout')

@section('content')
@if($action == 'autorizar')
@include('viajes.netos.partials.autorizar')
@elseif($action == 'validar')
@include('viajes.netos.partials.validar')
@endif
@stop
