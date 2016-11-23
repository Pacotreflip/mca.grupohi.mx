@extends('layout')

@section('content')
  <h1>{{ trans('strings.projects') }}</h1>
  <hr>

  <ul class="list-group">
    @foreach($proyectos as $proyecto)
    <a class="list-group-item" href="{{ route('context.set', [$proyecto->base_datos, $proyecto->id_proyecto]) }}">{{ mb_strtoupper($proyecto->descripcion) }}</a>
    @endforeach
  </ul>
  {!! $proyectos->render() !!}
@stop