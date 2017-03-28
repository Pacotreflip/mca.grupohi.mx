@extends('layout')

@section('content')
<h1>REPORTES</h1>
{!! Breadcrumbs::render('reportes.viajes_netos')  !!}
<hr>
<h3>BUSCAR VIAJES</h3>
<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label>FECHA INICIAL</label>
            <input type="text" name="FechaInicial" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>HORA INICIAL</label>
            <input type="text" name="HoraInicial" class="form-control">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>HORA FINAL</label>
            <input type="text" name="HoraFinal" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>FECHA FINAL</label>
            <input type="text" name="FechaFinal" class="form-control">
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('input[name=HoraInicial]').timepicker();
    $('input[name=HoraFinal]').timepicker();
</script>
@endsection