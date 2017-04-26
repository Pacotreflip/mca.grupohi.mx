@extends('layout')

@section('content')
    <h1>CORTES DE CHECADOR
        @if(auth()->user()->hasRole('checador'))
        <a href="{{ route('corte.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> CREAR CORTE DEL DÍA</a>
        @endif
    </h1>
    {!! Breadcrumbs::render('corte.index') !!}
    <hr>

    @if($cortes->count())
    <table class="table table-striped table-hover small" id="index_cortes">
        <thead>
        <tr>
            <th>Folio</th>
            <th>Checador</th>
            <th>Fecha y Hora del Corte</th>
            <th>Número de viajes</th>
            <th>Estado</th>
            <th>Editar</th>
            <th>PDF</th>
        </tr>
        </thead>
        <tbody>
            @foreach($cortes as $corte)
            <tr>
                <td><a href="{{ route('corte.show', $corte) }}">{{ $corte->id }}</a></td>
                <td>{{ $corte->checador->present()->nombreCompleto }}</td>
                <td>
                    {{ $corte->timestamp->format('d-M-Y h:m a') }}
                    <small class="text-muted">({{ $corte->timestamp->diffForHumans() }})</small>
                </td>
                <td>{{ $corte->corte_detalles->count() }}</td>
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
@endsection
@section('scripts')
    <script>
        var val_config = {
            auto_filter: true,

            col_0: 'input',
            col_1: 'select',
            col_2: 'input',
            col_3: 'select',
            col_4: 'select',
            col_5: 'none',
            col_6: 'none',

            base_path: App.tablefilterBasePath,
            paging: false,
            rows_counter: false,
            rows_counter_text: 'Cortes de Checador: ',
            btn_reset: true,
            btn_reset_text: 'Limpiar',
            clear_filter_text: 'Limpiar',
            loader: true,
            help_instructions: false,
            extensions: [{ name: 'sort' }]
        };
        var tf = new TableFilter('index_cortes', val_config);
        tf.init();
    </script>
@endsection