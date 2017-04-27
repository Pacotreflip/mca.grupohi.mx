@extends('layout')

@section('content')
    <h1>CORTE {{ $corte->id }} <small>({{ $corte->estado }})</small>
        @if($corte->estatus == 2)
        <a href="{{ route('pdf.corte', $corte) }}" class="btn btn-success pull-right"><i class="fa fa-file-pdf-o"></i> VER PDF</a>
        @elseif($corte->estatus == 1)
        <a href="{{ route('corte.edit', $corte) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> EDITAR CORTE</a>
        @endif
    </h1>
    {!! Breadcrumbs::render('corte.show', $corte) !!}
    <hr>
    <div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                DETALLES DEL CORTE
            </div>
            <div class="panel-body">
                <strong>CHECADOR: </strong> {{ $corte->checador->present()->nombreCompleto }}<br>
                <strong>FECHA y HORA DEL CORTE: </strong> {{ $corte->timestamp->format('d-M-Y h:i:s a') }} <small>({{$corte->timestamp->diffForHumans()}})</small> <br>
                <strong>NÚMERO DE VIAJES: </strong> {{$corte->corte_detalles->count() }}
            </div>
        </div>
    </div>
    </div>
    @if($corte->corte_detalles)
    <section id="corte-detalles">
        <hr>
        <ul id="detail-tabs" class="nav nav-tabs">
            @if($confirmados)
                <li class="active tab-corte"><a href="#confirmados" data-toggle="tab">VIAJES CONFIRMADOS</a></li>
            @endif
            @if($no_confirmados)
                @if(! $confirmados)
                    <li class="active tab-corte"><a href="#no_confirmados" data-toggle="tab">VIAJES NO CONFIRMADOS</a></li>
                @else
                    <li class="tab-corte"><a href="#no_confirmados" data-toggle="tab">VIAJES NO CONFIRMADOS</a></li>
                @endif
            @endif
        </ul>
        <div class="tab-content">
            @if($confirmados)
            <div id="confirmados" class="fade in active tab-pane table-responsive">
                <table class="table table-striped table-bordered small">
                    <thead>
                    <tr>
                        <th rowspan="2" style="text-align: center"> # </th>
                        <th rowspan="2" style="text-align: center"> Tipo </th>
                        <th rowspan="2" style="text-align: center"> Camión </th>
                        <th rowspan="2" style="text-align: center"> Ticket (Código) </th>
                        <th rowspan="2" style="text-align: center"> Fecha y Hora de Llegada </th>
                        <th rowspan="2" style="text-align: center"> Origen</th>
                        <th rowspan="2" style="text-align: center"> Tiro </th>
                        <th rowspan="2" style="text-align: center"> Material </th>
                        <th rowspan="2" style="text-align: center"> Cubicación	</th>
                        <th rowspan="2" style="text-align: center"> Importe </th>
                        <th rowspan="2" style="text-align: center"> Checador Primer Toque </th>
                        <th rowspan="2" style="text-align: center"> Checador Segundo Toque </th>
                        <th colspan="5" style="text-align: center"> Modificaciones</th>
                    </tr>
                    <tr>
                        <th style="text-align: center">Origen Nuevo</th>
                        <th style="text-align: center">Tiro Nuevo</th>
                        <th style="text-align: center">Material Nuevo</th>
                        <th style="text-align: center">Cubicación Nueva</th>
                        <th style="text-align: center">Justificación</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach($confirmados as $key => $viaje)
                        <tr >
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $viaje['tipo'] }}</td>
                            <td>{{ $viaje['camion'] }}</td>
                            <td>{{ $viaje['codigo'] }}</td>
                            <td>{{ $viaje['timestamp_llegada'] }}</td>
                            <td>{{ $viaje['origen'] }}</td>
                            <td>{{ $viaje['tiro'] }}</td>
                            <td>{{ $viaje['material'] }}</td>
                            <td style="text-align: right">{{ $viaje['cubicacion'] }} m<sup>3</sup></td>
                            <td style="text-align: right">${{ number_format($viaje['importe'], 2, ",", ".") }}</td>
                            <td>{{ $viaje['registro_primer_toque'] }}</td>
                            <td>{{ $viaje['registro'] }}</td>
                            <td>{{ $viaje['corte_cambio']['origen_nuevo'] }}</td>
                            <td>{{ $viaje['corte_cambio']['tiro_nuevo'] }}</td>
                            <td>{{ $viaje['corte_cambio']['material_nuevo'] }}</td>
                            @if($viaje['corte_cambio']['cubicacion_nueva'])
                                <td>{{ $viaje['corte_cambio']['cubicacion_nueva'] }} m<sup>3</sup></td>
                            @else
                                <td></td>
                            @endif
                            <td>{{ $viaje['corte_cambio']['justificacion'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @if($no_confirmados)
                @if($confirmados)
                    <div id="no_confirmados"class="fade in tab-pane table-responsive">
                @else
                    <div id="no_confirmados" class="active fade in tab-pane table-responsive">
                @endif
                        <table class="table table-striped table-bordered small">
                            <thead>
                            <tr>
                                <th style="text-align: center"> # </th>
                                <th style="text-align: center"> Tipo </th>
                                <th style="text-align: center"> Camión </th>
                                <th style="text-align: center"> Ticket (Código) </th>
                                <th style="text-align: center"> Fecha y Hora de Llegada </th>
                                <th style="text-align: center"> Origen</th>
                                <th style="text-align: center"> Tiro </th>
                                <th style="text-align: center"> Material </th>
                                <th style="text-align: center"> Cubicación	</th>
                                <th style="text-align: center"> Importe </th>
                                <th style="text-align: center"> Checador Primer Toque </th>
                                <th style="text-align: center"> Checador Segundo Toque </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($no_confirmados as $key => $viaje)
                                <tr >
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $viaje['tipo'] }}</td>
                                    <td>{{ $viaje['camion'] }}</td>
                                    <td>{{ $viaje['codigo'] }}</td>
                                    <td>{{ $viaje['timestamp_llegada'] }}</td>
                                    <td>{{ $viaje['origen'] }}</td>
                                    <td>{{ $viaje['tiro'] }}</td>
                                    <td>{{ $viaje['material'] }}</td>
                                    <td style="text-align: right">{{ $viaje['cubicacion'] }} m<sup>3</sup></td>
                                    <td style="text-align: right">${{ number_format($viaje['importe'], 2, ",", ".") }}</td>
                                    <td>{{ $viaje['registro_primer_toque'] }}</td>
                                    <td>{{ $viaje['registro'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            @endif
        </div>
    </section>
    @endif
@endsection