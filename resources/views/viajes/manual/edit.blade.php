@extends('layout')

@section('content')
<h1>AUTORIZACIÓN DE VIAJES MANUALES</h1>
<hr>
<div class="table-responsive col-md-10 col-md-offset-1 rcorners">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Fecha Llegada</th>
                <th>Hora Llegada</th>
                <th>Camión</th>
                <th>Tiro</th>
                <th>Origen</th>
                <th>Material</th>
                <th>Observaciones</th>
                <th><i style="color: green" class="fa fa-check"></i></th>
                <th><i style="color: red" class="fa fa-remove"></i></th>
                <th>Guardar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($viajes as $viaje)
            <tr>
                <td>{{ $viaje->FechaLlegada }}</td>
                <td>{{ $viaje->HoraLlegada }}
                <td>{{ $viaje->Camion }}</td>
                <td>{{ $viaje->Tiro }}</td>
                <td>{{ $viaje->Origen }}
                <td>{{ $viaje->Material }}
                <td>{{ $viaje->Observaciones }}
                {!! Form::open(['class' => 'viajeneto_update', 'method' => 'patch', 'route' => ['viajes.manual.update', $viaje]]) !!}
                <td><input id="{{$viaje->IdViajeNeto}}" type="checkbox" value="20" name="Estatus"/></td>
                <td><input id="{{$viaje->IdViajeNeto}}" type="checkbox" value="22" name="Estatus"/></td>
                <td><button type="submit" class="btn btn-success btn-xs"><i class="fa fa-save"></i></button></td>
                {!! Form::close() !!}
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </viajes-manual-autorizacion>
</div>
@stop
@section('scripts')
<script>
    $('.viajeneto_update').submit(function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        if(form.serializeArray().length > 2) {
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(respone) {
                },
                error: function(error) {
                    
                }
            });
        }
    });
    $("input:checkbox").click(function(e){
        if(this.checked) {
            var group = "input:checkbox[id='"+$(this).attr("id")+"']";
            $(group).prop("checked", false);
            $(this).prop("checked",true);
        }
});

</script>
@stop