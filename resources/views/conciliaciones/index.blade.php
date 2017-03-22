@extends('layout')

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
  <br>
</div>
<div class="table-responsive">
  <table class="table table-hover table-bordered small">
      <thead>
     
      <tr>
        <th style="width: 20px">#</th>
        <th style="text-align: center">Folio</th>
        <th style="text-align: center">Sindicato</th>
        <th style="text-align: center">Empresa</th>
        <th style="text-align: center">Número de Viajes</th>
        <th style="text-align: center">Volumen</th>
        <th style="text-align: center">Importe</th>
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
            {{$conciliacion->idconciliacion}}
            </td>
            <td>{{$conciliacion->sindicato->Descripcion}}</td>
            <td>{{$conciliacion->empresa}}</td>
            <td style="text-align: right">{{$conciliacion->conciliacionDetalles->where('estado', 1)->count()}}</td>
            <td style="text-align: right">{{$conciliacion->volumen_f}}</td>
            <td style="text-align: right">{{$conciliacion->importe_f}}</td>
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
                <a href="{{route('conciliaciones.edit', $conciliacion)}}" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
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
