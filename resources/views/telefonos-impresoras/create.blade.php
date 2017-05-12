@extends('layout')

@section('content')
    <h1>CONFIGURACIÓN TELÉFONOS-IMPRESORAS</h1>
    {!! Breadcrumbs::render('telefonos-impresoras.create') !!}
    <hr>
    @include('partials.errors')
    {!! Form::open(['route' =>['telefonos-impresoras.store'], 'method' => 'POST', 'id' => 'telefonos_impresoras_create_form']) !!}
    <div class="row">
        <!-- Teléfono -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="imei">IMEI TELÉFONO(*)</label>
                <select name="id_telefono" class="form-control select_tel_imp">
                    @foreach($telefonos as $key => $telefono)
                        <option value="{{ $key }}">{{$telefono}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Impresora -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="id_imprsora">MAC ADDRESS IMPRESORA(*)</label>
                <select name="id_impresora" class="form-control select_tel_imp">
                    @foreach($impresoras as $key => $impresora)
                        <option value="{{ $key }}">{{$impresora}}</option>
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
                var form = $('#telefonos_impresoras_create_form');
                swal({
                    title: "Guardar Cambios",
                    text: "¿Esta seguro de que desea guardar la configuración?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Guardar",
                    cancelButtonText: "No, Cancelar",
                    cancelButtonColor: "#ec6c62",
                    confirmButtonColor: "#467028"
                }, () => form.submit());
            });

            $('.select_tel_imp').val('');
            $('.select_tel_imp').select2({placeholder: 'Buscar...'});
        });
    </script>
@endsection