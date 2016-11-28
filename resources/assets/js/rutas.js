$(document).ready(function(){    
    $('#croquis').fileinput({
        language: 'es',
        showCaption: true,
        showUpload: false,
        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf']
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