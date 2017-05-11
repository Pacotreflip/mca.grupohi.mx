@extends('layout')
@section('content')
    <h1>IMPRESORAS</h1>
  {!! Breadcrumbs::render('impresoras.create')!!}
    <hr>
    @include('partials.errors')
    {!! Form::open(['route' =>['impresoras.store'], 'method' => 'POST', 'id' => 'impresora_form']) !!}
    <input type="hidden" value="{{auth()->user()->idusuario}}" name="registro"/>
    <div class="row">
        <!-- MAC Address Impresora  -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="mac">MAC Address Impresora(*)</label>
                <input type="text" name="mac" class="form-control" maxlength="12" >
            </div>
        </div>
        <!-- Marca Impresora  -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="marca">Marca(*)</label>
                <input type="text" name="marca" class="form-control" maxlength="200">
            </div>
        </div>
    </div>
     <div class="row">
        <!-- Modelo Impresora  -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="modelo">Modelo(*)</label>
                <input type="text" name="modelo" class="form-control" maxlength="200">
            </div>
        </div>
       
    </div>
    <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
    <div class="form-group">
        <button class="btn btn-success" type="submit" id="impresora_store">
            <i class="fa fa-save"></i> Guardar
        </button>
    </div>
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#impresora_store').off().on('click', function (e) {
                e.preventDefault();
                var form = $('#impresora_form');
                swal({
                    title: "Guardar Cambios",
                    text: "¿Esta seguro de que desea guardar la impresora?",
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