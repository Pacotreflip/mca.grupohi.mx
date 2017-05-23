@extends('layout')

@section('content')
    <h1>TELÉFONOS</h1>
    {!! Breadcrumbs::render('telefonos.create') !!}
    <hr>
    @include('partials.errors')
    {!! Form::open(['route' =>['telefonos.store'], 'method' => 'POST', 'id' => 'telefono_create_form']) !!}
    <input type="hidden" name="registro" value="{{ auth()->user()->idusuario }}" />
    <div class="row">
        <!-- Teléfono -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="imei">IMEI(*)</label>
                <input type="number" name="imei" maxlength="20" min="0" class="form-control">
            </div>
        </div>

        <!-- Linea Tlefónica -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="tel">LINEA TELEFÓNICA <strong>10 DÍGITOS</strong> (*)</label>
                <input name="linea" type="number" maxlength="10" min="0" class="form-control">
            </div>
        </div>
    </div>
     <div class="row">
        <!-- Teléfono -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="marca">MARCA(*)</label>
                <input type="text" name="marca" class="form-control">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="modelo">MODELO(*)</label>
                <input name="modelo" type="text" maxlength="10" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Checador -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="checador">CHECADOR(*)</label>
                <select name="id_checador" class="form-control select_tel_imp">
                    <option value="">--SELECCIONE--</option>
                    @foreach($checadores as $checador)
                        <option value="{{ $checador->idusuario }}">{{$checador->apaterno}} {{$checador->amaterno}} {{$checador->nombre}} </option>
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
        $(document).ready(function(){
            $('#telefono_store').off().on('click', function (e) {
                e.preventDefault();
                var form = $('#telefono_create_form');
                swal({
                    title: "Guardar Cambios",
                    text: "¿Esta seguro de que desea guardar la configuración del teléfono?",
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