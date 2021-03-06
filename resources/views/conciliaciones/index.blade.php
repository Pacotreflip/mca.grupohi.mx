@extends('layout_width')

@section('content')
<h1>{{ strtoupper(trans('strings.conciliaciones')) }}
    @if(Auth::user()->can(['generar-conciliacion']))
    <a href="{{ route('conciliaciones.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_conciliacion') }}</a>
    @endif
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
     <div class="row">
        <div class="col-md-12">
            @if(Auth::user()->can(['descargar-excel-conciliaciones']))
                <a  href="{{ route('xls.conciliaciones') }}" class="btn btn-primary btn-sm pull-right" style="margin-left: 5px"><i class="fa fa-file-excel-o"></i> DESCARGAR XLS</a>
            @endif
        </div>
    </div>
  <br>
</div>
<div class="table-responsive">
  <table class="table table-hover table-bordered small">
      <thead>
     
      <tr>
        <th style="width: 20px">#</th>
        <th style="text-align: center">Folio</th>
        <th style="text-align: center">Folio Histórico</th>
        <th style="text-align: center">Fecha Histórico</th>
        <th style="text-align: center">Sindicato</th>
        <th style="text-align: center">Empresa</th>
        <th style="text-align: center">Número de Viajes</th>
        <th style="text-align: center">Volumen Conciliado</th>
        <th style="text-align: center">Volumen Pagado</th>
        <th style="text-align: center">Importe Cociliado</th>
        <th style="text-align: center">Importe Pagado</th>
        <th style="text-align: center">Registró</th>
        <th style="text-align: center">Revisó</th>
        <th style="text-align: center">Aprobó</th>
        <th style="text-align: center">Estado</th>
        
          <th style="text-align: center">Editar</th>
          <th style="text-align: center">Cancelar</th>
          <th style="text-align: center">PDF</th>
      </tr>
    </thead>
    <tbody>
      @foreach($conciliaciones as $conciliacion)
        <tr>
            <td>
            {{$contador++}}
            </td>
             <td>
            {!! link_to_route('conciliaciones.show', $conciliacion->idconciliacion, $conciliacion->idconciliacion) !!}
            </td>
            <td>{{$conciliacion->Folio}}</td>
            <td>{{$conciliacion->fecha_conciliacion->format("d-m-Y")}}</td>
            <td>{{$conciliacion->sindicato->Descripcion}}</td>
            <td>{{$conciliacion->empresa}}</td>
            <td style="text-align: right">{{$conciliacion->conciliacionDetalles->where('estado', 1)->count()}}</td>
            <td style="text-align: right">{{$conciliacion->volumen_f}}</td>
           
            <td style="text-align: right">
                 @if($conciliacion->volumen_pagado_alert == "Pendiente")
                 <h5>
                 <span class="label label-warning">
                     {{$conciliacion->volumen_pagado_alert}}
                 </span>
                 </h5>
                 @elseif(($conciliacion->VolumenPagado-$conciliacion->volumen)>0.1)
                 <h5>
                 <span class="label label-danger">
                     {{$conciliacion->volumen_pagado_alert}}
                 </span>
                 </h5>
                 @else
                 {{$conciliacion->volumen_pagado_alert}}
                 
                @endif
                </td>
            <td style="text-align: right">{{$conciliacion->importe_f}}</td>
            <td style="text-align: right">
                @if($conciliacion->importe_pagado_alert == "Pendiente")
                <h5>
                 <span class="label label-warning">
                     {{$conciliacion->importe_pagado_alert}}
                 </span>
                </h5>
                 @elseif(($conciliacion->ImportePagado-$conciliacion->importe)>0.1)
                 <h5>
                 <span class="label label-danger">
                     {{$conciliacion->importe_pagado_alert}}
                 </span>
                 </h5>
                 @else
                 {{$conciliacion->importe_pagado_alert}}
                 
                @endif
                
            </td>
            
            <td>{{$conciliacion->registro }}<br>
                {{$conciliacion->fecha_hora_registro}}
            </td>
            <td>
            {{$conciliacion->cerro }}<br>
            {{$conciliacion->fecha_hora_cierre_str}}
            </td>
            <td>{{$conciliacion->aprobo }}<br>
            {{$conciliacion->fecha_hora_aprobacion_str}}</td>
            <td>{{$conciliacion->estado_str}}</td>
           
            <td>
                @if (Auth::user()->can(['generar-conciliacion', 'cerrar-conciliacion', 'aprobar-conciliacion', 'cancelar-conciliacion', 'abrir-conciliacion' ])) 
                <a href="{{route('conciliaciones.edit', $conciliacion)}}" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
                @else
                 <button disabled class="btn btn-primary btn-xs "><span class="glyphicon glyphicon-pencil"></span></button>
                @endif
            </td>
            <td>
                {!! Form::open(['route' => ['conciliaciones.destroy', $conciliacion]]) !!}
                @if($conciliacion->estado == -1 || $conciliacion->estado == -2 || !Auth::user()->can(['cancelar-conciliacion']))
                    <button disabled class="btn btn-danger btn-xs "><span class="glyphicon glyphicon-remove"></span></button>
                @else
                    <button class="btn btn-danger btn-xs cancelar_conciliacion"><span class="glyphicon glyphicon-remove"></span></button>
                @endif
                {!! Form::close() !!}
            </td>
            <td>
                @if($conciliacion->conciliacionDetalles->where('estado', 1)->count() && Auth::user()->can(['ver-pdf']))
                    <a href="{{ route('pfd.conciliacion', $conciliacion) }}" class="btn btn-default btn-xs"><i class="fa fa-file-pdf-o"></i></a>
                @else
                    <a class="btn btn-default btn-xs" disabled><i class="fa fa-file-pdf-o"></i></a>
                @endif
            </td>
        </tr>
      @endforeach
    </tbody>
  </table>
    <div class="text-center">
        {!! $conciliaciones->appends(['buscar' => Request::get('buscar')])->render() !!}
    </div>
</div>
@stop
