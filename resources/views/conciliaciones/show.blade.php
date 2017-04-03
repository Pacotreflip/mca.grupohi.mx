@extends('layout')

@section('content')
@include('partials.errors')
<h1>
    CONCILIACIONES
    @if(count($conciliacion->conciliacionDetalles->where('estado', 1)) && Auth::user()->can(['ver-pdf']))
        <a href="{{ route('pfd.conciliacion', $conciliacion) }}" class="btn btn-default btn-sm pull-right"><i class="fa fa-file-pdf-o"></i> VER PDF</a>
    @endif
</h1>
{!! Breadcrumbs::render('conciliaciones.show', $conciliacion) !!}
<section id="info">
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    DETALLES DE LA CONCILIACIÓN
                </div>
                <div class="panel-body">
                    <strong>Fecha: </strong>{{ $conciliacion->fecha_conciliacion }}<br>
                    <strong>Folio: </strong>{{ $conciliacion->Folio }}<br>
                    <strong>Rango de Fechas: </strong>{{ $conciliacion->rango }}<br>
                    <strong>Empresa: </strong>{{ $conciliacion->empresa->razonSocial }}<br>
                    <strong>Sindicato: </strong>{{ $conciliacion->sindicato->NombreCorto }}<br>
                    <strong>Estado: </strong>{{  $conciliacion->present()->conciliacionEstado }}<br>
                    <strong>Número de Viajes: </strong>{{ count($conciliacion->conciliacionDetalles->where('estado', 1)) }}<br>
                    <strong>Volúmen: </strong>{{ $conciliacion->volumen_f }} m<sup>3</sup><br>
                    <strong>Importe: </strong>$ {{ $conciliacion->importe_f }}<br>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel panel-heading">
                    DETALLES DE VIAJES MANUALES
                </div>
                <div class="panel-body">
                    <strong>Importe de viajes manuales: </strong>$ {{ $conciliacion->importe_viajes_manuales_f . ' (' . $conciliacion->porcentaje_importe_viajes_manuales . '%)'  }} <br>
                    <strong>Volúmen de viajes manuales: </strong>{{ $conciliacion->volumen_viajes_manuales_f }}  m<sup>3</sup>  ( {{ $conciliacion->porcentaje_volumen_viajes_manuales}} %)<br>
                </div>
            </div>
        </div>
        @if($conciliacion->estado == -1 || $conciliacion->estado == -2)
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        DETALLES DE LA CANCELACIÓN
                    </div>
                    <div class="panel-body">
                        <strong>Fecha y hora de cancelación: </strong>{{ $conciliacion->cancelacion->timestamp_cancelacion }}<br>
                        <strong>Persona que canceló: </strong>{{ $conciliacion->cancelacion->user->present()->nombreCompleto }}<br>
                        <strong>Motivo de la cancelación: </strong>{{ $conciliacion->cancelacion->motivo }}<br>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@if(count($conciliacion->conciliacionDetalles))
<section id="detalles">
    <hr>
    <ul id="detail-tabs" class="nav nav-tabs">
        @if(count($conciliacion->conciliacionDetalles->where('estado', 1)))
        <li class="active tab-conciliacion"><a href="#details" data-toggle="tab">VIAJES CONCILIADOS</a></li>
        @elseif(count($conciliacion->conciliacionDetalles->where('estado', -1)))
            @if(count($conciliacion->conciliacionDetalles->where('estado', -1)) && ! count($conciliacion->conciliacionDetalles->where('estado', 1)))
                <li class="active tab-conciliacion"><a href="#cancelados" data-toggle="tab">VIAJES CANCELADOS</a></li>
            @else
                <li class="tab-conciliacion"><a href="#cancelados" data-toggle="tab">VIAJES CANCELADOS</a></li>
            @endif
        @endif
    </ul>
    <div class="tab-content">
        @if(count($conciliacion->conciliacionDetalles->where('estado', 1)))
        <div id="details" class="fade in active tab-pane table-responsive">
            <table class="table table-striped table-bordered small">
                <thead>
                <tr>
                    <th>Fecha y Hora de Llegada</th>
                    <th>Camión</th>
                    <th>Cubicación</th>
                    <th>Material</th>
                    <th>Importe</th>
                    <th>Ticket (Código)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($conciliacion->conciliacionDetalles->where('estado', 1) as $detalle)
                    <tr>
                        <td>{{ $detalle->viaje->FechaLlegada.' ('.$detalle->viaje->HoraLlegada.')' }}</td>
                        <td>{{ $detalle->viaje->camion->Economico }}</td>
                        <td style="text-align: right">{{ $detalle->viaje->CubicacionCamion }}</td>
                        <td>{{ $detalle->viaje->material->Descripcion }}</td>
                        <td style="text-align: right">{{ number_format($detalle->viaje->Importe, 2, '.', ',') }}</td>
                        <td>{{ $detalle->viaje->code }}</td>
                    </tr>
                @endforeach
                @foreach($conciliacion->conciliacionDetalles->where('estado', 1) as $detalle)
                    <tr>
                        <td>{{ $detalle->viaje->FechaLlegada.' ('.$detalle->viaje->HoraLlegada.')' }}</td>
                        <td>{{ $detalle->viaje->camion->Economico }}</td>
                        <td style="text-align: right">{{ $detalle->viaje->CubicacionCamion }}</td>
                        <td>{{ $detalle->viaje->material->Descripcion }}</td>
                        <td style="text-align: right">{{ number_format($detalle->viaje->Importe, 2, '.', ',') }}</td>
                        <td>{{ $detalle->viaje->code }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @if(count($conciliacion->conciliacionDetalles->where('estado', -1)) && ! count($conciliacion->conciliacionDetalles->where('estado', 1)))
            <div id="cancelados"class="active fade in tab-pane table-responsive">
        @else
            <div id="cancelados" class="fade in tab-pane table-responsive">
        @endif
            <table class="table table-striped table-bordered small">
                <thead>
                <tr>
                    <th>Fecha y Hora de Llegada</th>
                    <th>Camión</th>
                    <th>Cubicación</th>
                    <th>Material</th>
                    <th>Importe</th>
                    <th>Ticket (Código)</th>
                    <th>Fecha Cancelación</th>
                    <th>Persona que Canceló</th>
                    <th>Motivo de Cancelación</th>
                </tr>
                </thead>
                <tbody>
                @foreach($conciliacion->conciliacionDetalles->where('estado', -1) as $detalle)
                <tr v-for="detalle in cancelados">
                    <td>{{ $detalle->viaje->FechaLlegada.' ('.$detalle->viaje->HoraLlegada.')' }}</td>
                    <td>{{ $detalle->viaje->camion->Economico }}</td>
                    <td style="text-align: right">{{ $detalle->viaje->CubicacionCamion }}</td>
                    <td>{{ $detalle->viaje->material->Descripcion }}</td>
                    <td style="text-align: right">{{ number_format($detalle->viaje->Importe, 2, '.', ',') }}</td>
                    <td>{{ $detalle->viaje->code }}</td>
                    <td>{{ $detalle->cancelacion->timestamp_cancelacion }}</td>
                    <td>{{ App\User::find($detalle->cancelacion->idcancelo)->present()->nombreCompleto }}</td>
                    <td>{{ $detalle->cancelacion->motivo }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif
@stop
