@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.rutas')) }}
  <a href="{{ route('rutas.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_ruta') }}</a>
</h1>
{!! Breadcrumbs::render('rutas.index') !!}
<hr>
<div class="table-responsive col-md-10 col-md-offset-1">
  <table class="table table-hover table-bordered">
      <thead>
      <tr>
          <th style="text-align: center" colspan="8">Ruta</th>
        <th style="text-align: center" colspan="3">Cronometría Activa</th>
        <th></th>
      </tr>
      <tr>
        <th>Ruta</th>
        <th>Origen</th>
        <th>Tiro</th>
        <th>Tipo</th>
        <th>1er. KM</th>
        <th>KM Subsecuentes</th>
        <th>KM Adicionales</th>
        <th>KM Total</th>
        <th>Tiempo Minimo</th>
        <th>Tiempo Tolerancia</th>
        <th>Fecha/Hora Registro</th>
        <th width="100px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rutas as $ruta)
        <tr>
          <td>
            <a href="{{ route('rutas.show', $ruta) }}">{{ $ruta->present()->claveRuta }}</a>
          </td>
          <td>{{ $ruta->origen->Descripcion }}</td>
          <td>{{ $ruta->tiro->Descripcion }}</td>
          <td>{{ $ruta->tipoRuta->Descripcion }}</td>
          <td>{{ $ruta->PrimerKm . ' km' }}</td>
          <td>{{ $ruta->KmSubsecuentes . ' km' }}</td>
          <td>{{ $ruta->KmAdicionales . ' km' }}</td>
          <td>{{ $ruta->TotalKM . ' km' }}</td>
          <td>{{ $ruta->cronometria->TiempoMinimo }}</td>
          <td>{{ $ruta->cronometria->Tolerancia }}</td>
          <td>{{ $ruta->present()->fechaYHoraRuta }}</td>
          <td>
              <a href="{{ route('rutas.edit', [$ruta]) }}" class="btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>
              @if($ruta->Estatus == 1)
              <a href="{{ route('rutas.destroy', [$ruta]) }}" class="btn btn-danger btn-sm element_destroy activo" title="Inhabilitar"><i class="fa fa-ban"></i></a>
              @else
              <a href="{{ route('rutas.destroy', [$ruta]) }}" class="btn btn-success btn-sm element_destroy inactivo" title="Habilitar"><i class="fa fa-check"></i></a>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop