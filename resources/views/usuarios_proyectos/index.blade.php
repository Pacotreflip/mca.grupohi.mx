@extends('layout')

@section('content')
    <h1>USUARIOS ASIGNADOS A PROYECTOS

        <a href="{{ route('usuario_proyecto.create') }}" class="btn btn-success pull-right" ><i class="fa fa-plus"></i> NUEVA ASIGNACIÓN </a>

    </h1>
    {!! Breadcrumbs::render('usuario_proyecto.index') !!}
    <hr>
     <div class="table-responsive">
         <table class="table table-bordered small">
             <thead>
             <tr>
                 <th>Id</th>
                 <th>Usuario</th>
                 <th>Proyecto</th>
                 <th>Fecha Y Hora Registro</th>
                 <th>Registró</th>
                 <th>Estatus</th>
                 <th style="width: 100px;">ACCIONES</th>
             </tr>
             </thead>
             <tbody>
             <span style="display: none">{{$aux=0}}</span>

             @foreach($usuarios as $usuario)

                 @if($aux!=$usuario->id_proyecto)
                     <tr>
                         <div style="display: none">{{$aux=$usuario->id_proyecto}}</div>

                         <th colspan="7" style="background-color: #dff0d8">{{$usuario->proyecto}}</th>
                         </tr>
                     @endif
                 <tr>
                     <th>{{$usuario->id_usuario}}</th>
                     <th>{{$usuario->nombre}}</th>
                     <th>{{$usuario->proyecto}}</th>
                     <th>{{$usuario->created_at}}</th>
                     <th>{{$usuario->registro}}</th>
                     <th>@if($usuario->estatus==1)<span>Activado</span>@else <span>Desactivado</span> @endif</th>
                     <th style="width: 100px;">
                         <a href="{{ route('usuario_proyecto.show', $usuario->id_usuario) }}" title="Ver" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                         <a href="{{ route('usuario_proyecto.edit', $usuario->id_usuario) }}" title="Editar" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>
                         @if($usuario->estatus == 1)
                             <button type="submit" title="Desactivar" class="btn btn-xs btn-danger" onclick="desactivar_usuario({{$usuario->id_usuario}},1)"><i class="fa fa-remove"></i></button>
                         @else
                             <button type="submit" title="Activar" class="btn btn-xs btn-success" onclick="activar_usuario({{$usuario->id_usuario}},0)"><i class="fa fa-check"></i></button>
                         @endif


                     </th>
                 </tr>
             @endforeach



             </tbody>
         </table>
     </div>
    <!-- Form eliminar Usuario -->
    <form id="eliminar_usuario" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="delete">
        <input type="hidden" name="motivo" value/>
        <input type="hidden" name="estatus" value/>
    </form>

@endsection

@section('scripts')
    <script>
        function desactivar_usuario(id,estatus) {
            var url = App.host + '/usuario_proyecto/' + id;
            var form = $('#eliminar_usuario');
            $('#estatus').val(estatus);
            swal({
                    title: "¡Desactivar Usuario!",
                    text: "¿Esta seguro de que deseas desactivar el usuario?",
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

        function activar_usuario(id,estatus) {
            $('#estatus').val(estatus);
            var url = App.host + '/usuario_proyecto/' + id;
            var form = $('#eliminar_usuario');

            swal({
                    title: "¡Activar Usuario!",
                    text: "¿Esta seguro de que deseas activar el usuario?",
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