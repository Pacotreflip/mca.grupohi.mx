@extends('layout')
@section('content')
    <h1>CONFIGURACIÓN DIARIA</h1>
    {!! Breadcrumbs::render('telefonos-impresoras.edit', $telefono) !!}
    <hr>

    @include('partials.errors')
    {!! Form::model($telefono, ['route' => ['telefonos-impresoras.update', $telefono], 'method' => 'PATCH', 'id' => 'telefono_impresora_update_form']) !!}
    <input type="hidden" name="registro" value="{{ auth()->user()->idusuario }}" />
    <div class="row">
        <!-- Teléfono -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="imei">TELÉFONO(*)</label>

                {!! Form::text('telefono', $telefono, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>

        <!-- Impresora -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="id_imprsora">IMPRESORA(*)</label>
                <select name="id_impresora" class="form-control select_tel_imp">
                    <option value="{{$telefono->impresora->id}}">{{$telefono->impresora}}</option>
                    @foreach($impresoras as $impresora)
                        <option value="{{ $impresora->id }}">{{$impresora}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
    <!-- Guardar -->
    <div class="form-group">
        <button class="btn btn-success" type="submit" id="telefono_impresora_update">
            <i class="fa fa-save"></i> Guardar
        </button>
    </div>
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#telefono_impresora_update').off().on('click', function (e) {
                e.preventDefault();
                var form = $('#telefono_impresora_update_form');
                swal({
                    title: "Guardar Cambios",
                    text: "¿Esta seguro de que desea actualizar la configuración?",
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