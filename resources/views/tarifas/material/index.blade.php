@extends('layout')

@section('content')
<div class='success'></div>
<h1>{{ strtoupper(trans('strings.tarifas_material')) }}
    @permission('crear-tarifas-material')
    <a href="{{ route('tarifas_material.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nueva Tarifa</a>
    @endpermission
    <a href="{{ route('csv.tarifas-material') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('tarifas_material.index') !!}
<hr>
<div class="errores"></div>
<div class="table-responsive">
    <table class="table table-hover table-bordered small">
        <thead>
            <tr>
                <th>Material</th>
                <th>Tarifa 1er. KM</th>
                <th>Tarifa KM Subsecuentes</th>
                <th>Tarifa KM Adicionales</th>
                <th>Inicio de Vigencia</th>
                <th>Fin de Vigencia</th>
                <th>Registro</th>
                <th>Fecha Hora Registro</th>
                <th>Estado</th>
                <th>Desactivó</th>
                <th>Motivo de Desactivación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tarifas as $tarifa)
            @if($tarifa->FinVigenciaTarifa == 'VIGENTE')
            <tr style="background-color: azure">
                @else
                <tr>
                @endif
                <td>{{ $tarifa->material->Descripcion }}{!! Form::hidden('IdMaterial', $tarifa->material->IdMaterial) !!}</td>
                <td>{{ $tarifa->PrimerKM }}</td>
                <td>{{ $tarifa->KMSubsecuente }}</td>
                <td>{{ $tarifa->KMAdicional }}</td>
                <td>{{ $tarifa->InicioVigencia->format("d-m-Y h:i:s") }}</td>
                <td>{{ $tarifa->FinVigenciaTarifa }}</td>
                <td>{{ $tarifa->registro->present()->NombreCompleto }}</td>
                <td>{{ $tarifa->Fecha_Hora_Registra->format("d-m-Y h:i:s") }}</td>
                <td>{{ $tarifa->estatus_string }}</td>
                <td>{{ $tarifa->user_desactivo }}</td>
                <td>{{ $tarifa->motivo }}</td>
                <td>
                    @permission('desactivar-tarifas-material')
                    @if($tarifa->Estatus == 1)
                        <button title="Desactivar" class="btn btn-xs btn-danger" onclick="desactivar_tarifa({{$tarifa->IdTarifa}})"><i class="fa fa-remove"></i></button>
                    @endif
                    @endpermission
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<form id='delete' method="post">
    <input type='hidden' name='motivo' value/>
    {{csrf_field()}}
    <input type="hidden" name="_method" value="delete"/>
</form>
@stop
@section('scripts')
    <script>
        function desactivar_tarifa(id) {
            var form = $('#delete');
            var url=App.host +"/tarifas_material/"+id;

            swal({
                title: "¡Desactivar tarifa!",
                text: "¿Esta seguro de que deseas desactivar la tarifa?",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Motivo de la desactivación.",
                confirmButtonText: "Si, Desactivar",
                cancelButtonText: "No, Cancelar",
                showLoaderOnConfirm: true

            },
            function(inputValue){
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("Escriba el motivo de la desactivación!");
                    return false
                }
                form.attr("action", url);
                $("input[name=motivo]").val(inputValue);
                form.submit();
            });
        }
    </script>
@endsection
