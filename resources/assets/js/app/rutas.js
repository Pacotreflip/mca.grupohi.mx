$(document).ready(function(){    
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
    $('input[name=TotalKM]').val(sum.toFixed(2));
}