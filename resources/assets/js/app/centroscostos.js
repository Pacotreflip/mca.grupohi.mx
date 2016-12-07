$(document).ready(function(){   
    if($('#centros_costos_table').length) {
        $('#centros_costos_table').treegrid();
        $('.centrocosto_create').off().on('click', function(event){
            event.preventDefault();
            showModal($(this).attr('href'));  
        });
    }
});

function storeCentroCosto() {
    $("#errores").empty();
    $.ajax({
        url: $('#centrocosto_store_form').attr('action'),
        type: 'POST',
        data: $('#centrocosto_store_form').serialize(),
        success: function(response) {
            $('#centrocosto_modal').modal('hide');
            swal({
                type: "success",
                title: response.message,   
                confirmButtonText: "OK",   
                closeOnConfirm: true 
            },
            function(){
                alert(response.ultimo);
                console.log($('#'+response.ultimo).length);
                $(response.view).insertAfter('#'+response.ultimo);
                //$('tr.treegrid-'+ response.ultimo).after(response.view);  
            });
        },
        error: function(xhr, responseText, thrownError) {
            var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
            if(ind1 === -1){
                var salida = '<div class="alert alert-danger" role="alert"><ul>';
                $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                    salida += '<li>'+elem+'</li>';
                });
                salida += '</ul></div>';
                $("#errores").html(salida);
            } else {
                var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
                var cad1 = xhr.responseText.substring(ind1);
                var ind2 = cad1.indexOf('</span>');
                var cad2 = cad1.substring(32,ind2);
                if(cad2 !== "") {
                    salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
                } else {
                    salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
                }
                salida += '</ul></div>';
                $("#errores").html(salida);
            }
        }
    });
}

function showModal(url) {
    $.ajax({
        url: url,
        method: 'GET',
        success: function (response) {
          $('#div_modal').html(response);
          $('#centrocosto_modal').modal('show');
        },
        error: function (error) {
          console.log(error);
        }
    });   
}