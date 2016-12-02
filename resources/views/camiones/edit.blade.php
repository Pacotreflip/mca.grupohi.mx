@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.edit')) }}</h1>
{!! Breadcrumbs::render('camiones.edit', $camion) !!}
<hr>
@include('partials.errors')

{!! Form::model($camion, ['method' => 'PATCH', 'route' => ['camiones.update', $camion], 'files' => true, 'id' => 'edit_camion']) !!}
<div class="id_camion" id='{{$camion->IdCamion}}'></div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('IdSindicato', 'Sindicato', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('IdSindicato', $sindicatos, null,  ['class' => 'form-control', 'placeholder' => 'Seleccione un Sindicato...']) !!}
            </div>
            {!! Form::label('Propietario', 'Propietario', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Propietario', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('IdOperador','Operador', ['class' => 'control-label col-sm-1'])  !!}
            <div class="col-sm-3">
                {!! Form::select('IdOperador', $operadores, null,  ['class' => 'form-control', 'placeholder' => 'Seleccione un Operador...']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Economico','Económico', ['class' => 'control-label col-sm-1'])  !!}
            <div class="col-sm-3">
                {!! Form::text('Economico',null, ['class' => 'form-control']) !!}
            </div>     
            {!! Form::label('Placas', 'Placas Camión', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Placas', null, ['class' => 'form-control']) !!}  
            </div>
            {!! Form::label('PlacasCaja', 'Placas Caja', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('PlacasCaja', null, ['class' => 'form-control']) !!}  
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('IdMarca', 'Marca', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('IdMarca', $marcas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Marca...']) !!}  
            </div>
            {!! Form::label('Modelo', 'Modelo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::text('Modelo', null, ['class' => 'form-control']) !!}  
            </div>
            {!! Form::label('IdBoton', 'Dispositivo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::select('IdBoton', $botones, null, ['class' => 'form-control' , 'placeholder' => 'Seleccione un Dispositivo...']) !!}  
            </div>
        </div>
    </fieldset>
</div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners" style="margin-top: 20px"> 
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Fotográfica</legend>
        <div class="form-group">
            {!! Form::label('Frente', 'Frente', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                <input id="f" name="Frente" type="file" class="file-loading imagen">
            </div>
            {!! Form::label('Derecha', 'Derecha', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                <input id="d" name="Derecha" type="file" class="file-loading imagen">
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('Atras', 'Atras', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                <input id="t" name="Atras" type="file" class="file-loading imagen">
            </div>
            {!! Form::label('Izquierda', 'Izquierda', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                <input id="i" name="Izquierda" type="file" class="file-loading imagen">
            </div>
        </div>
    </fieldset>
</div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners" style="margin-top: 20px">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-lock"></i> Información de Seguro</legend>
        <div class="form-group">
            {!! Form::label('Aseguradora', 'Aseguradora', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('Aseguradora', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('PolizaSeguro', 'Poliza', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('PolizaSeguro', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('VigenciaPolizaSeguro', 'Vigencia', ['class' => 'control-label col-sm-1']) !!} 
            <div class="col-sm-3">
                {!! Form::text('VigenciaPolizaSeguro', null, ['class' => 'form-control vigencia']) !!}
            </div>
        </div>
    </fieldset>
</div>
<div class="form-horizontal col-md-10 col-md-offset-1 rcorners" style="margin-top: 20px">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-arrows"></i> Información de Cubicación</legend>
        <div class="form-group">
            {!! Form::label('Ancho', 'Ancho', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::number('Ancho', null, ['class' => 'form-control ancho cubicacion', 'step' => 'any']) !!}
            </div>
            {!! Form::label('Largo', 'Largo', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::number('Largo', null, ['class' => 'form-control largo cubicacion', 'step' => 'any']) !!}
            </div>
            {!! Form::label('Alto', 'Alto', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::number('Alto', null, ['class' => 'form-control alto cubicacion', 'step' => 'any']) !!}
            </div>
            {!! Form::label('EspacioDeGato', 'Gato', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::number('EspacioDeGato', null, ['class' => 'form-control gato cubicacion', 'step' => 'any']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('AlturaExtension', 'Extensión', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-2">
                {!! Form::number('AlturaExtension', null, ['class' => 'form-control extension cubicacion', 'step' => 'any']) !!}
            </div>
            {!! Form::label('CubicacionReal', 'Cubicación Real', ['class' => 'control-label col-sm-1 col-sm-offset-1']) !!}
            <div class="col-sm-3">
                {!! Form::number('CubicacionReal', null, ['class' => 'form-control real', 'readonly' => 'readonly', 'step' => 'any']) !!}
            </div>
            {!! Form::label('CubicacionParaPago', 'Cubicación para Pago', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-3">
                {!! Form::number('CubicacionParaPago', null, ['class' => 'form-control pago', 'readonly' => 'readonly', 'step' => 'any']) !!}
            </div>
        </div>
    </fieldset>
</div>   
<div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
    <a class="btn btn-info" href="{{ URL::previous() }}">Regresar</a>        
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
</div>
{!! Form::close() !!}
@stop
@section('scripts')
<script>
$(document).ready(function(){    
    div = $('div.id_camion');
    id_camion = div.attr('id');
    $.ajax({
        type: 'GET',
        url: App.host + '/camion/' + id_camion + '/imagenes',
        success: function(response) {
            if(response.hasImagenes) {
                //arreglo de ids esperados
                $.each(response.data, function(key, data) {
                    var x = false;
                    $.each($('.imagen'), function(index, elem){
                       console.log(key.toString(), $(elem).attr('id'));
                       if (key.toString() == $(elem).attr('id')) {
                           x = true;
                       } 
                    });
                   if(x){
                    $("#"+key+"").fileinput({
                         language: 'es',
                         theme: 'fa',
                         showPreview: true,
                         showUpload: false,
                         browseOnZoneClick: true,
                         uploadUrl: false,
                         uploadAsync: true,
                         maxFileCount: 1,
                         autoReplace: true,
                         initialPreview: "<img src='"+data.url+"' class='file-preview-image' style='width:100%;height:auto; max-width: 100%; max-height: 100%;'>",                
                         initialPreviewAsData: false,
                         initialPreviewConfig: [data.data],
                         allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif'],
                         overwriteInitial: true,
                         initialCaption: [response.data.caption],
                         layoutTemplates: {
                             actionUpload: '',
                         },
                         previewZoomSettings: {
                             image: {width: "auto", height: "auto", 'max-width': "100%",'max-height': "100%"}                        }
                     });

                     $("#"+key+"").on("filepredelete", function(jqXHR) {
                         swal({   
                             title: "¿Estás seguro?",   
                             text: "¡Se eliminará la imagen y no podra recuperarla!",   
                             type: "warning",   
                             showCancelButton: true,   
                             confirmButtonColor: "#DD6B55",   
                             confirmButtonText: "Si, ¡Eliminar!",   
                             closeOnConfirm: false 
                         }, function(){   
                             $.ajax({
                                url: data.data.url,
                                type: 'POST',
                                data: {_method: 'delete', _token : App.csrfToken },
                                success: function(response) {
                                    if(response.success) {
                                        swal({   
                                            title: "¡Imagen Eliminada!",
                                            type: "success",   
                                            confirmButtonText: "OK",   
                                            closeOnConfirm: true }, 
                                        function(){   
                                            $('div.fileinput-remove').click();
                                        });
                                    } else {
                                        sweetAlert("Oops...", "¡Hubo un error al procesar la solicitud!", "error");
                                    }
                                },
                                error: function() {
                                     sweetAlert("Oops...", "¡Error Interno del Servidor!", "error");
                                }
                             });
                         });
                         return true;
                     });
                    } else {
                    console.log('key');
                        $("#"+key+"").fileinput({
                            language: 'es',
                            theme: 'fa',
                            showPreview: true,
                            showUpload: false,
                            uploadAsync: true,
                            maxFileConut: 1,
                            autoReplate: true,
                            browseOnZoneClick: true,
                            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif'],
                            layoutTemplates: {
                                actionUpload: '',
                                actionDelete: '',
                            },
                            previewSettings: {
                                image: {width: "125px", height: "auto"},
                            }
                        });
                    }
                });
            } else {
                $(".imagen").fileinput({
                    language: 'es',
                    theme: 'fa',
                    showPreview: true,
                    showUpload: false,
                    uploadAsync: true,
                    maxFileConut: 1,
                    autoReplate: true,
                    browseOnZoneClick: true,
                    allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif'],
                    layoutTemplates: {
                        actionUpload: '',
                        actionDelete: '',
                    },
                    previewSettings: {
                        image: {width: "125px", height: "auto"},
                    }
                });
            }
        }
    });
});
</script>
@stop