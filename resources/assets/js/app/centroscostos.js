$(document).ready(function(){   
    if($('#centros_costos_table').length) {
        $('#centros_costos_table').treegrid();
        $(document).on('click', '.centrocosto_create', function(event){
            event.preventDefault();
            showModal($(this).attr('href'));  
        });
        $(document).on('click', '.centrocosto_edit', function(event){
            event.preventDefault();
            showModal($(this).attr('href'));  
        });
        $(document).on('click', '.centrocosto_destroy', function(event){
            event.preventDefault();
            deleteCentroCosto($(this).attr('href'));
        });
        $(document).on('click', '.centrocosto_toggle', function(event){
            event.preventDefault();
            toggleCentroCosto(this, $(this).attr('href'));
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
                text: response.message,   
                title: '',
                confirmButtonText: "OK",   
                closeOnConfirm: true 
            },
            function(){
                $(response.view).insertAfter('#'+response.ultimo);
                $('#centros_costos_table').treegrid();
                $('#'+response.id).focus();
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

function updateCentroCosto() {
    $("#errores").empty();
    $.ajax({
        url: $('#centrocosto_update_form').attr('action'),
        type: 'POST',
        data: $('#centrocosto_update_form').serialize(),
        success: function(response) {
            $('#centrocosto_modal').modal('hide');
            swal({
                type: "success",
                text: response.message,   
                title: '',
                confirmButtonText: "OK",   
                closeOnConfirm: true 
            },
            function(){
                $('#'+response.id).replaceWith(response.view);
                $('#centros_costos_table').treegrid();
                $('#'+response.id).focus();
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
        error: function(xhr, responseText, thrownError) {
            alert('Un error Ocurrió');
        }
    });   
}

function deleteCentroCosto(url) {
    swal({   
        title: "¿Estás seguro?",   
        text: "¡Se eliminará el Centro de Costo y no podra recuperarlo!",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Si, ¡Eliminar!",   
        closeOnConfirm: false 
    }, function(){   
        $.ajax({
            url: url,
            type: 'POST',
            data: {_method: 'delete', _token : App.csrfToken },
            success: function(response) {
                if(response.success) {
                    swal({   
                        title: "¡Marca Eliminada!",
                        type: "success",   
                        confirmButtonText: "OK",   
                        closeOnConfirm: true }, 
                    function(){   
                        $('#'+response.id).remove();
                        $('#centros_costos_table').treegrid();
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
}    

function toggleCentroCosto(e, url) {
    var element = $(e);
    $.ajax({
        url: url,
        type: 'POST',
        data: {_method: 'delete', _token: App.csrfToken, _toggle: true},
        success: function(response) {
            if(element.hasClass('activo')) {
                element.removeClass('btn-danger activo').addClass('btn-success inactivo');
                element.text('ACTIVAR');  
            } else {
                element.removeClass('btn-success inactivo').addClass('btn-danger activo');
                element.text('DESACTIVAR');
            }
            swal(response.text, "", "success");
        },
        error: function() {
            sweetAlert("Oops...", "¡Error Interno del Servidor!", "error");
        }
    });
}