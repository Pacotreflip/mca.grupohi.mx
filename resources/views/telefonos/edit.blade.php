@extends('layout')
@section('content')
    <h1>TELÉFONOS</h1>
    {!! Breadcrumbs::render('telefonos.edit', $telefono) !!}
    <hr>

    @include('partials.errors')
    {!! Form::model($telefono, ['route' => ['telefonos.update', $telefono], 'method' => 'PATCH', 'id' => 'telefono_update_form']) !!}
    <input type="hidden" name="registro" value="{{ auth()->user()->idusuario }}" />
    <div class="row">
        <!-- Teléfono -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="imei">IMEI(*)</label>
                {!! Form::text('imei', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Linea Tlefónica -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="linea">LINEA TELEFÓNICA <strong>10 DÍGITOS</strong> (*)</label>
                {!! Form::text('linea', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
    <!-- Guardar -->
    <div class="form-group">
        <button class="btn btn-success" type="submit" id="telefono_update">
            <i class="fa fa-save"></i> Guardar
        </button>
    </div>
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#telefono_update').off().on('click', function (e) {
                e.preventDefault();
                var form = $('#telefono_update_form');
                swal({
                    title: "Guardar Cambios",
                    text: "¿Esta seguro de que desea actualizar la información del teléfono?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Actualizar",
                    cancelButtonText: "No, Cancelar",
                    cancelButtonColor: "#ec6c62",
                    confirmButtonColor: "#467028"
                }, () => form.submit());
            })
        });
    </script>
@endsection