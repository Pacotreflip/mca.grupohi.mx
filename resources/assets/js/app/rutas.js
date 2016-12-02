$(document).ready(function(){    
    div = $('div.id_ruta');
    id_ruta = div.attr('id');
    $.ajax({
        type: 'GET',
        url: App.host + '/ruta/' + id_ruta + '/archivos',
        success: function(response) {
            if(response.hasCroquis) {
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
                           url: response.data.url,
                           type: 'POST',
                           data: {_method: 'delete', _token : App.csrfToken },
                           success: function(response) {
                               if(response.success) {
                                   swal({   
                                       title: "¡Croquis Eliminado!",
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
                    allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf'],
                    layoutTemplates: {
                        actionUpload: '',
                        actionDelete: ''
                    }
                });
            }
        }
    });
    
    $("#croquis").fileinput({
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
        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf'],
        layoutTemplates: {
            actionUpload: '',
            actionDelete: ''
        }
    });
    
    calcularTotal();
    
    $('input.km').keyup(function(){   
        calcularTotal(this);
    });
});

function calcularTotal() {
    var sum = 0;
    var form = $('form');
    form.find('input.km').each(function(i, e) {
        var val = parseFloat($(e).val());
        if( !isNaN( val ) ) {
            sum += val;
        }
    });
    $('.totalKm').val(sum.toFixed(2));
}

$(".rutas_destroy").off().on("click", function(event) {
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
                    btn.html('<i class="fa fa-plus"></i> ACTIVAR');
                } else {
                    btn.text('ACTIVAR');
                }
            } else {
                btn.removeClass('btn-success inactivo').addClass('btn-danger activo');
                if(!btn.hasClass('btn-sm')) {
                    var i = btn.closest('i');
                    btn.html('<i class="fa fa-close"></i> ELIMINAR');
                } else {
                    btn.text('ELIMINAR');
                }
            }
            swal(response.text, "", "success");
        },
        error: function() {
            sweetAlert("Oops...", "¡Error Interno del Servidor!", "error");
        }
    });
});