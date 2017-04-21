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
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Folio</th>
            <th>Checador</th>
            <th>Fecha y Hora de Corte</th>
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
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <div class="text-center">
        {!! $cortes->appends(['buscar' => $busqueda])->render() !!}
    </div>

@endsection