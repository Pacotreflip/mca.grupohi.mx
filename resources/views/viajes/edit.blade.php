@extends('layout')

@section('content')
    @if($action == 'revertir')
        @include('viajes.partials.revertir')
    @endif
@stop