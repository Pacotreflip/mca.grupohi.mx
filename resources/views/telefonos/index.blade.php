@extends('layout')

@section('content')
    <h1>TELÉFONOS
        <a href="{{ route('telefonos.create') }}" class="btn btn-success pull-right" ><i class="fa fa-plus"></i> Nuevo Teléfono </a>
    <a href="{{ route('csv.telefonos')}}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
    </h1>
    {!! Breadcrumbs::render('telefonos.index') !!}
    <hr>
     <div class="table-responsive">
         <table class="table table-hover table-striped small">
             <thead>
             <tr>
                 <th>ID</th>
                 <th>IMEI Teléfono</th>
                 <th>Linea Telefónica</th>
                 <th>Marca</th>
                 <th>Modelo</th>
                 <th>Fecha Y Hora Registro</th>
                 <th>Registró</th>
                 <th>Acciones</th>
             </tr>
             </thead>
             <tbody>
             @foreach($telefonos as $telefono)
                 <tr>
                     <td>{{ $telefono->id }}</td>
                     <td>{{ $telefono->imei }}</td>
                     <td>{{ $telefono->linea }}</td>
                     <td>{{ $telefono->marca }}</td>
                     <td>{{ $telefono->modelo }}</td>
                     <td>{{ $telefono->created_at->format('d-M-Y h:i:s a') }}</td>
                     <td>{{ $telefono->user_registro->present()->nombreCompleto() }}</td>
                     <td>
                         <a href="{{ route('telefonos.show', $telefono) }}" title="Ver" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                         <a href="{{ route('telefonos.edit', $telefono) }}" title="Editar" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>
                         @if($telefono->estatus == 1)
                         <button type="submit" title="Desactivar" class="btn btn-xs btn-danger" onclick="desactivar_telefono({{$telefono->id}})"><i class="fa fa-remove"></i></button>
                         @else
                         <button type="submit" title="Activar" class="btn btn-xs btn-success" onclick="activar_telefono({{$telefono->id}})"><i class="fa fa-plus"></i></button>
                         @endif
                     </td>
                 </tr>
             @endforeach
             </tbody>
         </table>
     </div>

    <!-- Form eliminar Teléfono -->
    <form id="eliminar_telefono" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="delete">
        <input type="hidden" name="motivo" value/>
    </form>
@endsection
@section('scripts')
    <script>
        function desactivar_telefono(id) {
            var url = App.host + '/telefonos/' + id;
            var form = $('#eliminar_telefono');

            swal({
                title: "¡Eliminar Teléfono!",
                text: "¿Esta seguro de que deseas eliminar el teléfono?",
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

        function activar_telefono(id) {
            var url = App.host + '/telefonos/' + id;
            var form = $('#eliminar_telefono');

            swal({
                    title: "¡Activar Teléfono!",
                    text: "¿Esta seguro de que deseas activar el teléfono?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    inputPlaceholder: "Motivo de la activación.",
                    confirmButtonText: "Si, Activar",
                    cancelButtonText: "No, Cancelar",
                    showLoaderOnConfirm: true

                },
                function(){
                    form.attr("action", url);
                    $("input[name=motivo]").val("");
                    form.submit();
                });
        }
    </script>
@endsection