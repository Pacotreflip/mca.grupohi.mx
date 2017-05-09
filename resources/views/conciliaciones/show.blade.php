@extends('layout')

@section('content')
@include('partials.errors')
<h1>
    CONCILIACIONES
    @if(Auth::user()->can(['ver-pdf']))
        <a href="{{ route('pfd.conciliacion', $conciliacion) }}" target="_blank"  class="btn btn-default btn-sm pull-right"><i class="fa fa-file-pdf-o"></i> VER PDF</a>
    @endif
    @if(Auth::user()->can(['descargar-excel-conciliacion']))
        <a  href="{{ route('xls.conciliacion', $conciliacion) }}" class="btn btn-default btn-sm pull-right" style="margin-left: 5px"><i class="fa fa-file-excel-o"></i> DESCARGAR XLS</a>
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
                    <strong>Importe de viajes manuales: </strong>$ {{ $conciliacion->importe_viajes_manuales_f . ' (' . $conciliacion->porcentaje_importe_viajes_manuales . ' %)'  }} <br>
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
@if(count($conciliacion->conciliacionDetalles) || count($conciliacion->conciliacionDetallesNoConciliados) )
<section id="detalles">
    <hr>
    <ul id="detail-tabs" class="nav nav-tabs">
        @if(count($conciliacion->conciliacionDetalles->where('estado', 1)))
        
        <li class="active tab-conciliacion"><a href="#details" data-toggle="tab">VIAJES CONCILIADOS</a></li>
        @endif
        @if(count($conciliacion->conciliacionDetallesNoConciliados))
        <li class="tab-conciliacion"><a href="#no_conciliados_details" data-toggle="tab">VIAJES NO CONCILIADOS</a></li>
        
        @endif
        @if(count($conciliacion->conciliacionDetalles->where('estado', -1)))
            @if(! count($conciliacion->conciliacionDetalles->where('estado', 1)))
                <li class="active tab-conciliacion"><a href="#cancelados" data-toggle="tab">VIAJES CANCELADOS</a></li>
            @else
                <li class="tab-conciliacion"><a href="#cancelados" data-toggle="tab">VIAJES CANCELADOS</a></li>
            @endif
            @if(count($conciliacion->conciliacionDetallesNoConciliados))
                <li class="tab-conciliacion"><a href="#no_conciliados_details" data-toggle="tab">VIAJES NO CONCILIADOS</a></li>
            @endif
        @endif
    </ul>
    <div class="tab-content">
        @if(count($conciliacion->conciliacionDetalles->where('estado', 1)))
        <div id="details" class="fade in active tab-pane table-responsive">
            <table class="table table-striped table-bordered small">
                @if($conciliacion->viajes_manuales()->count())
                <thead>
                <tr>
                    <td colspan="6" style="text-align: center">
                        <strong>VIAJES CARGADOS MANUALMENTE</strong>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: center">Camión</th>
                    <th style="text-align: center">Ticket (Código)</th>
                    <th style="text-align: center">Registró</th>
                    <th style="text-align: center">Fecha y Hora de Llegada</th>
                    <th style="text-align: center">Material</th>
                    <th style="text-align: center">Cubicación</th>
                    <th style="text-align: center">Importe</th>
                </tr>
                </thead>
                <tbody>
                @foreach($conciliacion->viajes_manuales() as $viaje)
                    <tr>
                        <td>{{ $viaje->camion->Economico }}</td>
                        <td>{{ $viaje->code }}</td>
                        <td>{{ $viaje->usuario_registro }}</td>
                        <td>{{ $viaje->FechaLlegada.' ('.$viaje->HoraLlegada.')' }}</td>
                        <td>{{ $viaje->material->Descripcion }}</td>
                        <td style="text-align: right">{{ $viaje->CubicacionCamion }} m<sup>3</sup></td>
                        <td style="text-align: right">$ {{ number_format($viaje->Importe, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="5">
                            <strong>TOTAL VIAJES MANUALES</strong>
                        </td>
                        <td style="text-align: right"><strong>{{ $conciliacion->volumen_viajes_manuales_f }} m<sup>3</sup></strong></td>
                        <td style="text-align: right"><strong>$ {{ $conciliacion->importe_viajes_manuales_f }}</strong></td>
                    </tr>
                @endif

                <!-- VIAJES MÓVILES -->
                    @if($conciliacion->viajes_moviles()->count())
                    <tr><td colspan="7"></td></tr>
                    <tr>
                        <th colspan="7" style="text-align: center">
                            <strong>VIAJES CARGADOS DESDE APLICACIÓN MÓVIL</strong>
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: center">Camión</th>
                        <th style="text-align: center">Ticket (Código)</th>
                        <th style="text-align: center">Registró</th>
                        <th style="text-align: center">Fecha y Hora de Llegada</th>
                        <th style="text-align: center">Material</th>
                        <th style="text-align: center">Cubicación</th>
                        <th style="text-align: center">Importe</th>
                    </tr>
                    @foreach($conciliacion->viajes_moviles() as $viaje)
                        <tr>
                            <td>{{ $viaje->camion->Economico }}</td>
                            <td>{{ $viaje->code }}</td>
                            <td>{{ $viaje->usuario_registro }}</td>
                            <td>{{ $viaje->FechaLlegada.' ('.$viaje->HoraLlegada.')' }}</td>
                            <td>{{ $viaje->material->Descripcion }}</td>
                            <td style="text-align: right">{{ $viaje->CubicacionCamion }} m<sup>3</sup></td>
                            <td style="text-align: right">$ {{ number_format($viaje->Importe, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                        <tr>
                            <td colspan="5">
                                <strong>TOTAL VIAJES MÓVILES</strong>
                            </td>
                            <td style="text-align: right"><strong>{{ $conciliacion->volumen_viajes_moviles_f }} m<sup>3</sup></strong></td>
                            <td style="text-align: right"><strong>$ {{ $conciliacion->importe_viajes_moviles_f }}</strong></td>
                        </tr>
                    @endif
                <tr><td colspan="7"></td> </tr>
                <tr>
                    <td colspan="5"><strong>TOTAL</strong></td>
                    <td style="text-align: right"><strong>{{ $conciliacion->volumen_f }} m<sup>3</sup></strong></td>
                    <td style="text-align: right"><strong>$ {{ $conciliacion->importe_f }}</strong></td>
                </tr>
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
                    <th style="text-align: center">Camión</th>
                    <th style="text-align: center">Ticket (Código)</th>
                    <th style="text-align: center">Fecha y Hora de Llegada</th>
                    <th style="text-align: center">Material</th>
                    <th style="text-align: center">Cubicación</th>
                    <th style="text-align: center">Importe</th>
                    <th style="text-align: center">Fecha Cancelación</th>
                    <th style="text-align: center">Persona que Canceló</th>
                    <th style="text-align: center">Motivo de Cancelación</th>
                </tr>
                </thead>
                <tbody>
                @foreach($conciliacion->conciliacionDetalles->where('estado', -1) as $detalle)
                <tr>
                    <td>{{ $detalle->viaje->camion->Economico }}</td>
                    <td>{{ $detalle->viaje->code }}</td>
                    <td>{{ $detalle->viaje->FechaLlegada.' ('.$detalle->viaje->HoraLlegada.')' }}</td>
                    <td>{{ $detalle->viaje->material->Descripcion }}</td>
                    <td style="text-align: right">{{ $detalle->viaje->CubicacionCamion }} m<sup>3</sup></td>
                    <td style="text-align: right">$ {{ number_format($detalle->viaje->Importe, 2, '.', ',') }}</td>
                    <td>{{ $detalle->cancelacion->timestamp_cancelacion }}</td>
                    <td>{{ App\User::find($detalle->cancelacion->idcancelo)->present()->nombreCompleto }}</td>
                    <td>{{ $detalle->cancelacion->motivo }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        
         @if(count($conciliacion->conciliacionDetallesNoConciliados) && ! count($conciliacion->conciliacionDetalles->where('estado', 1)))
        <div id="no_conciliados_details" class="fade in tab-pane table-responsive active">
            @else
             <div id="no_conciliados_details" class="fade in tab-pane table-responsive">
            @endif
        <table class="table table-striped table-bordered small">
            <thead>
            <tr>
                <th style="text-align: center">Ticket (Código)</th>
                <th style="text-align: center">Registro Intento</th>
                <th style="text-align: center; width: 130px">Fecha y Hora Intento Conciliación </th>
                <th style="text-align: center">Motivo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($conciliacion->conciliacionDetallesNoConciliados as $detalle)
            <tr >
                <td>{{ $detalle->Code }}</td>
                <td>{{ $detalle->usuario_registro }}</td>
                <td>{{ $detalle->timestamp }}</td>
                <td>{{ $detalle->detalle }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        
    </div>
</section>
@endif
@stop
