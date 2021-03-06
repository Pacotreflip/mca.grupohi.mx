$(".marcas_destroy").off().on("click", function(event) {
    var btn = $(this);    
    event.preventDefault();
    swal({   
        title: "¿Estás seguro?",   
        text: "¡Se eliminará la marca y no podra recuperarla!",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Si, ¡Eliminar!",   
        closeOnConfirm: false 
    }, function(){   
        $.ajax({
           url: btn.attr('href'),
           type: 'POST',
           data: {_method: 'delete', _token : App.csrfToken },
           success: function(response) {
               if(response.success) {
                   swal({   
                       title: "¡Marca Eliminada!",
                       type: "success",   
                       confirmButtonText: "OK",   
                       closeOnConfirm: false }, 
                   function(){   
                       window.location.href = response.url;
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
});