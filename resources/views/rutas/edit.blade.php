@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.edit_ruta')) }}</h1>
{!! Breadcrumbs::render('rutas.edit', $ruta) !!}
<hr>
@include('partials.errors')

{!! Form::model($ruta, ['method' => 'PATCH', 'route' => ['rutas.update', $ruta], 'files' => true]) !!}
<div class="id_ruta" id='{{$ruta->IdRuta}}'></div>
<div class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-info-circle"></i> Información Básica</legend>
        <div class="form-group">
            {!! Form::label('IdOrigen', 'Origen', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                {!! Form::select('IdOrigen', $origenes, null, ['placeholder' => 'Seleccione un Origen...', 'class' => 'form-control']) !!}
            </div>
            {!! Form::label('IdTiro', 'Tiro', ['class' => 'control-label col-sm-1']) !!}
            <div class="col-sm-5">
                {!! Form::select('IdTiro', $tiros, null, ['placeholder' => 'Seleccione un Tiro...', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('IdTipoRuta','Tipo de Ruta', ['class' => 'control-label col-sm-2'])!!}
            <div class="col-sm-10">
                {!! Form::select('IdTipoRuta', $tipos, null, ['placeholder' => 'Seleccione un Tipo...', 'class' => 'form-control']) !!}
            </div>
        </div>
    </fieldset>
</div>
<div style="margin-top: 20px" class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-tachometer"></i> Kilometraje</legend>
        <div class="form-group">
            {!! Form::label('PrimerKm', 'Primer KM', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('PrimerKm', 1, ['class' => 'form-control km', 'readonly' => 'readonly']) !!}
            </div>
            {!! Form::label('KmSubsecuentes', 'KM Subsecuentes', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('KmSubsecuentes', null, ['class' => 'form-control km']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('KmAdicionales', 'KM Adicionales', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('KmAdicionales', null, ['class' => 'form-control km']) !!}
            </div>
            {!! Form::label('TotalKM', 'KM Total', ['class' => 'control-label col-sm-3']) !!}
            <div class="col-sm-3">
                {!! Form::number('TotalKM', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
            </div>
        </div>
    </fieldset>    
</div>
<div style="margin-top: 20px" class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-clock-o"></i> Cronometría</legend>
        <div class="form-group">
            {!! Form::label('TiempoMinimo', 'Tiempo Mínimo (min)', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::number('TiempoMinimo', $ruta->cronometria->TiempoMinimo, ['class' => 'form-control']) !!}
            </div>
            {!! Form::label('Tolerancia', 'Tolerancia (min)', ['class' => 'control-label col-sm-2']) !!}
            <div class="col-sm-4">
                {!! Form::number('Tolerancia', $ruta->cronometria->Tolerancia, ['class' => 'form-control']) !!}
            </div>
        </div>
        </legend>
    </fieldset>
</div>
<div style="margin-top: 20px" class="form-horizontal col-md-6 col-md-offset-3 rcorners">
    <fieldset>
        <legend class="scheduler-border"><i class="fa fa-map-o"></i> Croquis</legend>
        <div class="form-group" style="text-align: center">         
            <div class="col-sm-12" style="text-align: center">
                <input id="croquis-edit" name="Croquis" type="file" class="file-loading">
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
    $(document).ready(function() {
        div = $('div.id_ruta');
        id_ruta = div.attr('id');
        console.log(id_ruta);
        $.ajax({
            type: 'GET',
            url: App.host + '/archivos/' + id_ruta,
            success: function(response) {
                if (response.type === 'application/pdf') {
                    preview = "<embed src='"+response.url+"' type='application/pdf' width='100%' height='100%'>";
                } else {
                    preview = "<img src='"+response.url+"' class='file-preview-image' style='width:100%;height:auto; max-width: 100%; max-height: 100%;'>";
                }
                $("#croquis-edit").fileinput({
                    language: 'es',
                    theme: 'fa',
                    showPreview: true,
                    showUpload: false,
                    browseOnZoneClick: true,
                    uploadUrl: false,
                    uploadAsync: true,
                    maxFileCount: 1,
                    dropZoneTitle: '<p style="font-size: 25px"><small><strong>Selecciona ó arrastra</strong> un archivo de croquis</small></p>',
                    dropZoneClickTitle: '',
                    autoReplace: true,
                    initialPreview: [preview],                
                    initialPreviewAsData: false,
                    initialPreviewConfig: [response.data],
                    allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf'],
                    overwriteInitial: true,
                    initialCaption: [response.data.caption],
                    layoutTemplates: {
                        actionUpload: '',
                    },
                    previewZoomSettings: {
                        image: {width: "auto", height: "auto", 'max-width': "100%",'max-height': "100%"},
                        pdf: {width: "100%", height: "100%", 'min-height': "480px"},
                    }
                });
                
                $("#croquis-edit").on("filepredelete", function(jqXHR) {
                    swal({   
                        title: "¿Estás seguro?",   
                        text: "¡Se eliminará el croquis y no podra recuperarlo!",   
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Si, ¡Eliminar!",   
                        closeOnConfirm: false 
                    }, function(){   
                        $.ajax({
                           url: response.url_delete,
                           type: 'POST',
                           data: {_method: 'delete', _token : App.csrfToken },
                           success: function(response) {
                               if(response.success) {
                                   swal({   
                                       title: "¡Croquis Eliminado!",
                                       type: "success",   
                                       confirmButtonText: "OK",   
                                       closeOnConfirm: false }, 
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
            }
        });
     }); 
</script>
@stop