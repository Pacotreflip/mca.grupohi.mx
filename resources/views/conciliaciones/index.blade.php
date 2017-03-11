@extends('layout')

@section('content')
<h1>CONCILIACIONES
  <a href="{{ route('conciliaciones.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_ruta') }}</a>
</h1>
<hr>
<div class="table-responsive col-md-10 col-md-offset-1">
  <table class="table table-hover table-bordered">
      <thead>
     
      <tr>
        <th>#</th>
        <th>Sindicato</th>
        <th>Empresa</th>
        <th>Número de Viajes</th>
        <th>Vólumen</th>
        <th>Importe</th>
        <th>Registró</th>
        <th>Fecha/Hora Registro</th>
        <th width="100px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($conciliaciones as $conciliacion)
        <tr>
          <td>
            d
          </td>
         
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@stop