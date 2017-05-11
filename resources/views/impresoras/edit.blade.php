@extends('layout')
@section('content')
    <h1>IMPRESORAS</h1>
    {!! Breadcrumbs::render('impresoras.edit',$impresora) !!}
    <hr>
    @include('partials.errors')
    {!! Form::model($impresora,['route' =>['impresoras.update', $impresora], 'method' => 'PATCH', 'id' => 'impresora_update_form']) !!}
    
    <input type="hidden" value="{{auth()->user()->idusuario}}" name="registro"/>
    <div class="row">
        <!-- MAC Address Impresora  -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="mac">MAC Address Impresora(*)</label>
                {!! Form::text('mac',null,['class'=>'form-control','maxlength'=>12]) !!}
            </div>
        </div>
        <!-- Marca Impresora  -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="marca">Marca(*)</label>
               {!! Form::text('marca',null,['class'=>'form-control','maxlength'=>200]) !!}
            </div>
        </div>
    </div>
     <div class="row">
        <!-- Modelo Impresora  -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="modelo">Modelo(*)</label>
                  {!! Form::text('modelo',null,['class'=>'form-control','maxlength'=>200]) !!}
            </div>
        </div>
       
    </div>
    <p class="small">Los campos <strong>(*)</strong> son obligatorios.</p>
    <div class="form-group">
        <button class="btn btn-success" type="submit" id="impresora_update">
            <i class="fa fa-save"></i> Guardar
        </button>
    </div>
    {!! Form::close() !!}

@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#impresora_update').off().on('click', function (e) {
                e.preventDefault();
                var form = $('#impresora_update_form');
                swal({
                    title: "Guardar Cambios",
                    text: "¿Esta seguro de que desea actualizar la información de la impresora?",
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