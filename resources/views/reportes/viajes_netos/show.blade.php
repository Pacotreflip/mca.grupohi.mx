@extends('layout')
@section('content')
@include('reportes.viajes_netos.partials.table', ['data' => $data, 'request' => $request])
@stop