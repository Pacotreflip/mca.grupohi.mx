@extends('layout')

@section('content')
    <h1>REPORTES</h1>
    {!! Breadcrumbs::render('reportes.viajes_netos')  !!}
    <hr>
    <h3>BUSCAR VIAJES</h3>
    {!! Form::open(['method' => 'GET', 'route' => ['reportes.viajes_netos.show'], 'id' => 'form_reporte_viajes_netos']) !!}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>FECHA INICIAL</label>
                <input type="text" class="date start form-control" name="FechaInicial" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>HORA INICIAL</label>
                <input type="text" class="time start form-control" name="HoraInicial" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>FECHA FINAL</label>
                <input type="text" class="date end form-control" name="FechaFinal" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>HORA FINAL</label>
                <input type="text" class="time end form-control" name="HoraFinal" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success submit">GENERAR REPORTE</button>
    </div>
    {!! Form::close() !!}
@endsection
@section('scripts')
    <script>
        // initialize input widgets first
        $('#form_reporte_viajes_netos .time').timepicker({
            'timeFormat' : 'hh:mm:ss a',
            'showDuration': true,
        });

        $('#form_reporte_viajes_netos .date').datepicker({
            format: 'yyyy-mm-dd',
            language: 'es',
            autoclose: true,
            clearBtn: true,
            todayHighlight: true,
            endDate: '0d'
        });

        // initialize datepair
        var form_reporte_viajes_netos = document.getElementById('form_reporte_viajes_netos');
        new Datepair(form_reporte_viajes_netos);

        $('.submit').off().on('click', function(e) {
            e.preventDefault();

            var url = $('#form_reporte_viajes_netos').attr('action');
            var data = $('#form_reporte_viajes_netos').serializeArray();

            console.log(url);
            console.log(data);

            $.ajax({
                type: 'GET',
                url: url,
                data: data,
                success: function (response) {
                    $('#reporte_viajes_netos').html(response);
                },
                error: function (error) {

                }
            });
        });
    </script>
@endsection