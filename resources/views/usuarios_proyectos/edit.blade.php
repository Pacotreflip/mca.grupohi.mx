@extends('layout')

@section('content')
    <h1>MODIFICACIÓN DE USUARIO A PROYECTO</h1>
    {!! Breadcrumbs::render('usuario_proyecto.edit') !!}
    <hr>
    @include('partials.errors')
    {!! Form::open(['route' =>['usuario_proyecto.update'], 'method' => 'PUT', 'id' => 'usuario_update_form']) !!}
    <input type="hidden" name="registro" value="{{ auth()->user()->idusuario }}" />
    <input type="hidden" name="idUsuario" value="{{ $catalogos['actual'][0]->id_usuario}}" />

    <div class="row">
        <!-- Proyecto -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="id_proyecto">Proyecto(*)</label>
                <select name="id_proyecto" class="form-control comboBusqueda" >
                    <option value="">[--SELECCIONE--]</option>
                    @foreach($catalogos['proyectos'] as $proyecto)
                        <option value="{{ $proyecto->id_proyecto}}"  @if($catalogos['actual'][0]->id_proyecto==$proyecto->id_proyecto)selected @endif >{{$proyecto->descripcion}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Usuario -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="id_usuario">Usuario (*)</label>
                <select name="id_usuario" class="form-control comboBusqueda">
                    <option value="">[--SELECCIONE--]</option>

                    @foreach($catalogos['usuarios']  as $usuario)
                        <option  @if($catalogos['actual'][0]->id_usuario_intranet==$usuario->idusuario)selected @endif  value="{{ $usuario->idusuario }}">{{$usuario->nombre}} {{$usuario->apaterno}} {{$usuario->amaterno}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>

    <!-- Guardar -->
    <div class="form-group">
        <button class="btn btn-success" type="submit" id="usuario_update">
            <i class="fa fa-save"></i> Guardar
        </button>
    </div>
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#usuario_update').off().on('click', function (e) {
                e.preventDefault();
                var form = $('#usuario_update_form');
                swal({
                    title: "Guardar Cambios",
                    text: "¿Esta seguro de que desea guardar la configuración del usuario?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Guardar",
                    cancelButtonText: "No, Cancelar",
                    cancelButtonColor: "#ec6c62",
                    confirmButtonColor: "#467028"
                }, () => form.submit());
            })
        });

        $('.comboBusqueda').select2();
    </script>
@endsection