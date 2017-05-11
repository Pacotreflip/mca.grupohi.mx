@extends('layout')

@section('content')
    <h1>CONFIGURACIÓN DE TELÉFONOS</h1>
    {!! Breadcrumbs::render('telefonos.create') !!}
    <hr>
    @include('partials.errors')
    {!! Form::open(['route' =>['telefonos.store'], 'method' => 'POST', 'id' => 'telefono_create_form']) !!}
    <div class="row">
        <!-- Teléfono -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="imei">IMEI(*)</label>
                <input type="text" name="imei" class="form-control">
            </div>
        </div>

        <!-- Impresora -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="id_impresora">IMPRESORA</label>
                <select name="id_impresora" class="form-control">
                    <option value>-- SELECCIONE --</option>
                    @foreach($impresoras as $impresora)
                        <option class="{{ $impresora->id }}">{{ $impresora->mac }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
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