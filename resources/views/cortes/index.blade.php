@extends('layout')

@section('content')
    <h1>CORTES DE CHECADOR
        @if(auth()->user()->hasRole('checador'))
        <a href="{{ route('corte.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> CREAR CORTE DEL DÍA</a>
        @endif
    </h1>
    {!! Breadcrumbs::render('corte.index') !!}
    <hr>
    @include('partials.search-form')

    @if($cortes->count())
    <table class="table table-striped table-hover small">
        <thead>
        <tr>
            <th>Folio</th>
            <th>Checador</th>
            <th>Fecha y Hora de Corte</th>
            <th>Estado</th>
            <th>Editar</th>
            <th>PDF</th>
        </tr>
        </thead>
        <tbody>
            @foreach($cortes as $corte)
            <tr>
                <td><a href="{{ route('corte.show', $corte) }}"># {{ $corte->id }}</a></td>
                <td>{{ $corte->checador->present()->nombreCompleto }}</td>
                <td>
                    {{ $corte->timestamp->format('d-M-Y h:m a') }}
                    <small class="text-muted">({{ $corte->timestamp->diffForHumans() }})</small>
                </td>
                <td>{{ $corte->estado }}</td>
                <td>
                    @if($corte->estatus == 1)
                        <a href="{{route('corte.edit', $corte)}}" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
                    @else
                        <button disabled class="btn btn-primary btn-xs "><span class="glyphicon glyphicon-pencil"></span></button>
                    @endif
                </td>
                <td>
                    @if($corte->estatus == 2)
                        <a href="{{ route('pdf.corte', $corte) }}" class="btn btn-info btn-xs"><i class="fa fa-file-pdf-o"></i></a>
                    @else
                        <a class="btn btn-info btn-xs" disabled><i class="fa fa-file-pdf-o"></i></a>
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <div class="text-center">
        {!! $cortes->appends(['buscar' => $busqueda])->render() !!}
    </div>

@endsection