@extends('layout')

@section('content')
    <h1>ASIGNACIÓN DE USUARIO A PROYECTO</h1>

    {!! Breadcrumbs::render('usuario_proyecto.create') !!}
    <hr>
    @include('partials.errors')
    {!! Form::open(['route' =>['usuario_proyecto.store'], 'method' => 'POST', 'id' => 'usuario_create_form']) !!}
    <input type="hidden" name="registro" value="{{ auth()->user()->idusuario }}" />
    <div class="row">
        <!-- Proyecto -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="id_proyecto">Proyecto(*)</label>
                <select name="id_proyecto" class="form-control"  @role('administracion-permisos') disabled="true" @endrole>
                    <option value="">[--SELECCIONE--]</option>
                    @foreach($catalogos['proyectos'] as $proyecto)
                        <option value="{{ $proyecto->id_proyecto}}"   @role('administracion-permisos') selected @endrole    @if(old('id_proyecto')==$proyecto->id_proyecto)selected @endif >{{$proyecto->descripcion}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Usuario -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="id_usuario">Usuario (*)</label>
                <select name="id_usuario" class="form-control select_tel_imp">
                    <option value="">[--SELECCIONE--]</option>

                    @foreach($catalogos['usuarios']  as $usuario)
                        <option  @if(old('id_usuario')==$usuario->idusuario)selected @endif  value="{{ $usuario->idusuario }}">{{$usuario->nombre}} {{$usuario->apaterno}} {{$usuario->amaterno}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>

    <!-- Guardar -->
    <div class="form-group">
        <button class="btn btn-success" type="submit" id="usuario_store">
            <i class="fa fa-save"></i> Guardar
        </button>
    </div>
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#usuario_store').off().on('click', function (e) {
                e.preventDefault();
                var form = $('#usuario_create_form');
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
    </script>
@endsection