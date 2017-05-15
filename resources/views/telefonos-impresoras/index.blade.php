@extends('layout')

@section('content')
<h1>CONFIGURACIÓN DIARIA
    <a href="{{ route('telefonos-impresoras.create') }}" class="btn btn-success pull-right" ><i class="fa fa-plus"></i> Nueva Configuración </a>
    <a href="{{ route('pdf.telefonos-impresoras')}}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-pdf-o"></i> Descargar PDF</a>


</h1>
{!! Breadcrumbs::render('telefonos-impresoras.index') !!}
<hr>
<div class="panel-group" id="accordion">
    @foreach($configuraciones as $configuracion)

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$configuracion->id}}">
                    <b> IMEI:</b> {{$configuracion->imei}}/<b> MAC ADRESS:</b> {{$configuracion->impresora->mac}}
                    <div class="pull-right">
                        <a href="{{ route('telefonos-impresoras.edit',$configuracion->id) }}" title="Editar" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>
                        <button type="button" title="Eliminar" class="btn btn-xs btn-danger" onclick="eliminar_configuracion({{ $configuracion->id}});"><i class="fa fa-remove"></i></button>
                    </div>
                </a>
            </h4>
        </div>
        <div id="collapse{{$configuracion->id}}" class="panel-collapse collapse">
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td colspan="2"><center><b>TELÉFONO</b></center></td>
                    </tr>
                    <tr>
                        <td style="width: 50%"><b>ID:</b></td>
                        <td>{{$configuracion->id}}</td>
                    </tr>
                    <tr>
                        <td><b>IMEI:</b></td>
                        <td>{{$configuracion->imei}}</td>
                    </tr>
                    <tr>
                        <td><b>LINEA TELEFÓNICA:</b></td>
                        <td>{{$configuracion->linea}}</td>
                    </tr>
                    <tr>
                        <td><b>MARCA:</b></td>
                        <td>{{$configuracion->marca}}</td>
                    </tr>
                    <tr>
                        <td><b>MODELO:</b></td>
                        <td>{{$configuracion->modelo}}</td>
                    </tr>
                </table>
                </td>
                <td>
                    <table class="table table-bordered table-striped" >
                        <tr>
                            <td colspan="2" ><center><b>IMPRESORA</b></center></td>
                </tr>
                <tr>
                    <td style="width: 50%"><b>ID:</b></td>
                    <td>{{$configuracion->impresora->id}}</td>
                </tr>
                <tr>
                    <td><b>MAC ADRESS:</b></td>
                    <td>{{$configuracion->impresora->mac}}</td>
                </tr>
                <tr>
                    <td><b>MARCA:</b></td>
                    <td>{{$configuracion->impresora->marca}}</td>
                </tr>
                <tr>
                    <td><b>MODELO:</b></td>
                    <td>{{$configuracion->impresora->modelo}}</td>
                </tr>
                </table>
                </td>

                </tr>
                </table>


            </div>
        </div>
    </div>

    @endforeach
</div>
<!-- Form eliminar Configuracion -->
<form id="eliminar_configuracion" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="delete">
    <input type="hidden" name="motivo" value/>
</form>
@endsection

@section('scripts')
<script>
    function eliminar_configuracion(id) {
    var url = App.host + '/telefonos-impresoras/' + id;
    var form = $('#eliminar_configuracion');
    swal({
    title: "¡Eliminar Configuración!",
            text: "¿Esta seguro de que deseas eliminar la configuración?",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "Motivo de la eliminación.",
            confirmButtonText: "Si, Eliminar",
            cancelButtonText: "No, Cancelar",
            showLoaderOnConfirm: true

    },
            function(inputValue){
            if (inputValue === false) return false;
            if (inputValue === "") {
            swal.showInputError("Escriba el motivo de la eliminación!");
            return false
            }
            form.attr("action", url);
            $("input[name=motivo]").val(inputValue);
            form.submit();
            });
    }
</script>
@endsection