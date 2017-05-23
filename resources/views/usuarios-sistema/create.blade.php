@extends('layout')
@section('content')
    <h1>USUARIO SISTEMA</h1>
    {!! Breadcrumbs::render('telefonos.create') !!}
    <hr>
    @include('partials.errors')
    {!! Form::open(['route' =>['usuarios_sistema.store'], 'method' => 'POST', 'id' => 'usuarios_sistema']) !!}
    <input type="hidden" name="registro" value="{{ auth()->user()->idusuario }}" />
    <div class="row">
        <!-- Nombre -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="nombre">NOMBRE(*)</label>
                <input type="text" name="nombre" maxlength="45" class="form-control letras" value="{{ old('nombre')}}">
            </div>
        </div>

        <!-- AP PATERNO -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="apaterno">AP. PATERNO(*)</label>
                <input name="apaterno" type="text" maxlength="45" class="form-control letras" value="{{ old('apaterno')}}">
            </div>
        </div>
        <!-- AP MATERNO -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="amaterno">AP. MATERNO(*)</label>
                <input name="amaterno" type="text" maxlength="45" class="form-control letras" value="{{ old('amaterno')}}">
            </div>
        </div>
    </div>

    <div class="row">
        <!-- GENERO -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="idgenero">GENERO(*)</label>
                <select name="idgenero" class="form-control">
                    <option value="">[--SELECCIONE--]</option>
                    @foreach($catalogos['generos'] as $genero)
                        <option value="{{ $genero->idgenero}}"  @if(old('idgenero')==$genero->idgenero)selected @endif>{{$genero->genero}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!--RFC -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="rfc">RFC</label>
                <input name="rfc" type="text" maxlength="13" class="form-control alfanum" value="{{ old('rfc')}}">
            </div>
        </div>
        <!-- TITULO -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="idtitulo">TITULO (*)</label>
                <select name="idtitulo" class="form-control">
                    <option value="">[--SELECCIONE--]</option>
                    @foreach($catalogos['titulos'] as $titulo)
                        <option value="{{ $titulo->idtitulo}}"   @if(old('idtitulo')==$titulo->idtitulo)selected @endif>{{$titulo->titulo}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- USUARIO -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="usuario">USUARIO(*)</label>
                <input type="text" name="usuario" maxlength="45" class="form-control alfanum" value="{{ old('usuario')}}">
            </div>
        </div>

        <!-- CLAVE -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="clave">CLAVE(*)</label>
                <input name="clave" type="password" maxlength="45" class="form-control alfanum">
            </div>
        </div>
        <!-- CONFIRMA CLAVE -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="clavec">CONFIRMA CLAVE(*)</label>
                <input name="clavec" type="password" maxlength="45" class="form-control alfanum">
            </div>
        </div>
    </div>

    <div class="row">
        <!-- CORREO -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="correo">CORREO</label>
                <input type="text" name="correo" maxlength="20" class="form-control" value="{{ old('correo')}}">
            </div>
        </div>

        <!-- EXTENSION -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="extension">EXTENSIÓN</label>
                <input name="extension" type="text" maxlength="10" class="form-control alfanum " value="{{ old('extension')}}">
            </div>
        </div>
        <!-- FNAC -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="fnacimiento">FECHA NACIMIENTO(*)</label>
                <input type="text" class="date start form-control" name="fnacimiento" value="{{ old('fnacimiento') }}" placeholder="yyyy-mm-dd"  />
            </div>
        </div>
    </div>

    <div class="row">
        <!-- UBICACION -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="idubicacion">UBICACIÓN(*)</label>
                <select name="idubicacion" class="form-control">
                    <option value="">[--SELECCIONE--]</option>
                    @foreach($catalogos['ubicaciones'] as $ubicacion)
                        <option value="{{ $ubicacion->idubicacion}}"   @if(old('idubicacion')==$ubicacion->idubicacion)selected @endif>{{$ubicacion->ubicacion}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- EMPRESA -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="idempresa">EMPRESA(*)</label>
                <select name="idempresa" class="form-control">
                    <option value="">[--SELECCIONE--]</option>
                    @foreach($catalogos['empresas'] as $empresa)
                        <option value="{{ $empresa->idempresa}}"   @if(old('idempresa')==$empresa->idempresa)selected @endif>{{$empresa->empresa}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- DEPARTAMENTO-->
        <div class="col-md-4">
            <div class="form-group">
                <label for="iddepartamento">DEPARTAMENTO(*)</label>
                <select name="iddepartamento" class="form-control">
                    <option value="">[--SELECCIONE--]</option>
                    @foreach($catalogos['departamentos'] as $departamento)
                        <option value="{{ $departamento->iddepartamento}}"  @if(old('iddepartamento')==$departamento->iddepartamento)selected @endif>{{$departamento->departamento}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- PROYECTO -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="proyecto">PROYECTO(*)</label>
                <select name="proyecto" class="form-control">
                    <option value="">[--SELECCIONE--]</option>
                    @foreach($catalogos['proyectos'] as $proyecto)
                        <option value="{{ $proyecto->id_proyecto}}"  @if(old('proyecto')==$proyecto->id_proyecto)selected @endif >{{$proyecto->descripcion}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
  <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>

    <!-- Guardar -->
    <div class="form-group">
        <button class="btn btn-success" type="submit" id="telefono_store">
            <i class="fa fa-save"></i> Guardar
        </button>
    </div>
    {!! Form::close() !!}

@endsection

@section('scripts')
    <script>
    $('.date').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
        endDate: '0d'
    });


</script>
    @endsection