$(document).ready(function(){   
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

    $('.vigencia').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true,
        clearBtn: true,
        todayHighlight: true
    });

    $('input.cubicacion').keyup(function(){   
        calcularCubicaciones();
    });
});

function calcularCubicaciones() {
    var dim = 0;
    var ancho = parseFloat($('#create_camion input.ancho').val());
    var largo = parseFloat($('#create_camion input.largo').val());
    var alto = parseFloat($('#create_camion input.alto').val());
    var gato = parseFloat($('#create_camion input.gato').val());
    var ext = parseFloat($('#create_camion input.extension').val());

    if(!isNaN(ancho) && !isNaN(largo) && !isNaN(alto)) {
        dim = ancho*largo*alto;
        if(!isNaN(gato)) {
            dim -= gato;
        }
        if(!isNaN(ext)) {
            dim += (ancho*largo*ext);
        }
    }
    $('#create_camion input.real').val(dim.toFixed(2));
    $('#create_camion input.pago').val(Math.round(dim.toFixed(2)));
}