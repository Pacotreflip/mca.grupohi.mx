$(document).ready(function(){
    //CREATE
    if($('#create_camion').length) {
        $('#create_camion input.imagen').fileinput({
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
    
    //EDIT
    if($('#edit_camion').length) {
        var div = $('div.id_camion');
        var id_camion = div.attr('id');
        $.ajax({
            type: 'GET',
            url: App.host + '/camion/' + id_camion + '/imagenes',
            success: function(response) {
                $.each($('.imagen'), function(i, element) {
                    var exists = false;
                    var data = null;
                    $.each(response.data, function(key, value) {
                        if($(element).attr('id') == key.toString()) {
                            exists = true;
                            data = value;
                        }
                    });
                    if(exists) {
                        $(element).fileinput({
                            language: 'es',
                            theme: 'fa',
                            showPreview: true,
                            showUpload: false,
                            uploadUrl: false,
                            uploadAsync: true,
                            maxFileCount: 1,
                            autoReplace: true,
                            initialPreview: data.url,                
                            initialPreviewAsData: true,
                            initialPreviewFileType: 'image',
                            initialPreviewConfig: [data.data],
                            dropZoneTitle: '<p style="font-size: 25px"><small><strong>Selecciona ó arrastra</strong> una imagen</small></p>',
                            dropZoneClickTitle: '',
                            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif'],
                            overwriteInitial: true,
                            initialCaption: [data.data.caption],
                            layoutTemplates: {
                                actionUpload: '',
                            },
                            previewSettings: {
                                image: {width: "120px", height: "100px", 'max-width': "100%",'max-height': "100%"}                        
                            },
                            deleteExtraData: {
                                _method: 'delete',
                                _token: App.csrfToken
                            }
                        });

                        $(element).on("filepredelete", function(jqXHR) {
                            var abort = true;
                            if (confirm("¿Estás seguro de querer eliminar la imagen?")) {
                                abort = false;
                            }
                            return abort;
                        });
                        
                        $(element).on('filedeleted', function(event, key){
                           swal('¡Imagen Eliminada!', "", "success");
                        });
                    } else {
                        $(element).fileinput({
                            language: 'es',
                            theme: 'fa',
                            showPreview: true,
                            showUpload: false,
                            browseOnZoneClick: true,
                            uploadUrl: false,
                            uploadAsync: true,
                            maxFileCount: 1,
                            dropZoneTitle: '<p style="font-size: 25px"><small><strong>Selecciona ó arrastra</strong> una imagen</small></p>',
                            dropZoneClickTitle: '',
                            autoReplace: true,
                            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif'],
                            layoutTemplates: {
                                actionUpload: '',
                                actionDelete: ''
                            },
                            previewSettings: {
                                image: {width: "120px", height: "100px", 'max-width': "100%",'max-height': "100%"}
                            }
                        });
                    }    
                });
                $('div.fileinput-remove').attr('style', 'display:none');
            }
        });
    }
    
    //GENERALES
    $('input.cubicacion').keyup(function(){   
        calcularCubicaciones();
    });
    
    $('input.cubicacion').change(function(){
       calcularCubicaciones(); 
    });
    
    function calcularCubicaciones() {
        var dim = 0;
        var ancho = parseFloat($('input.ancho').val());
        var largo = parseFloat($('input.largo').val());
        var alto = parseFloat($('input.alto').val());
        var gato = parseFloat($('input.gato').val());
        var dism = parseFloat($('input.disminucion').val());
        var ext = parseFloat($('input.extension').val());

        if(!isNaN(ancho) && !isNaN(largo) && !isNaN(alto)) {
            dim = ancho*largo*alto;
            if(!isNaN(gato)) {
                dim -= gato;
            }
            if(!isNaN(ext)) {
                dim += (ancho*largo*ext);
            }
            if(!isNaN(dism)) {
                dim -= dism;
            }
        }
        $('input.real').val(dim.toFixed(2));
        $('input.pago').val(Math.round(dim.toFixed(2)));
    }
    
    $(".camiones_destroy").off().on("click", function(event) {
        var btn = $(this);   
        event.preventDefault();
        $.ajax({
            url: btn.attr('href'),
            type: 'POST',
            data: {_method: 'delete', _token: App.csrfToken },
            success: function(response) {
                if(btn.hasClass('activo')) {
                    btn.removeClass('btn-danger activo').addClass('btn-success inactivo');
                    if(!btn.hasClass('btn-sm')) {
                        var i = btn.closest('i');
                        btn.html('<i class="fa fa-check"></i> HABILITAR');
                    } else {
                        btn.html('<i class="fa fa-check"></i>');
                        btn.attr('title', 'Habilitar');
                    }
                } else {
                    btn.removeClass('btn-success inactivo').addClass('btn-danger activo');
                    if(!btn.hasClass('btn-sm')) {
                        var i = btn.closest('i');
                        btn.html('<i class="fa fa-ban"></i> INHABILITAR');
                    } else {
                        btn.html('<i class="fa fa-ban"></i>');
                        btn.attr('title', 'Inhabilitar');
                    }
                }
                swal(response.text, "", "success");
            },
            error: function() {
                sweetAlert("Oops...", "¡Error Interno del Servidor!", "error");
            }
        });
    });
});