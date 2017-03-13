@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.conciliaciones')) }}
  <a href="{{ route('conciliaciones.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_conciliacion') }}</a>
</h1>
<hr>
<div >
    <div class="row">
        <div class="col-md-6">
            @if(Request::get('buscar')!= "")
             <h4>
            Resultados para: <strong>{{Request::get('buscar')}}</strong>
        </h4>
            @endif
        </div>
        <div class="col-md-6 text-right" >
    <form class="form-inline" action="{{route("conciliaciones.index")}}">
       
        <div class="input-group">
            <input type="text" name="buscar" class='form-control input-sm' placeholder="buscar..." value="{{Request::get('buscar')}}" />
          <span class="input-group-btn">
            <button class="btn btn-sm btn-primary" type="submit">Buscar</button>
          </span>
        </div>
    </form>
        </div>
    </div>
  <br>
</div>
<div class="table-responsive col-md-10 col-md-offset-1">
  <table class="table table-hover table-bordered">
      <thead>
     
      <tr>
        <th style="width: 20px">#</th>
        <th>Folio</th>
        <th>Sindicato</th>
        <th>Empresa</th>
        <th>Número de Viajes</th>
        <th>Vólumen</th>
        <th>Importe</th>
        <th>Registró</th>
        <th>Fecha/Hora Registro</th>
      </tr>
    </thead>
    <tbody>
      @foreach($conciliaciones as $conciliacion)
        <tr>
            <td>
            {{$contador++}}
            </td>
             <td>
            {{$conciliacion->idconciliacion}}
            </td>
            <td>{{$conciliacion->sindicato->Descripcion}}</td>
            <td>{{$conciliacion->empresa->razonSocial}}</td>
            <td style="text-align: right">{{$conciliacion->conciliacionDetalle->count()}}</td>
            <td style="text-align: right">{{$conciliacion->volumen_f}}</td>
            <td style="text-align: right">{{$conciliacion->importe_f}}</td>
            <td>{{$conciliacion->usuario->present()->nombreCompleto}}</td>
            <td>{{$conciliacion->fecha_hora_registro }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
     {!! $conciliaciones->appends(['buscar' => Request::get('buscar')])->render() !!}
</div>
@stop