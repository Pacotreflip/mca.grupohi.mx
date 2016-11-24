$(".materiales_destroy").off().on("click", function(event) {
    var btn = $(this);
    var tr = btn.closest('tr');
    event.preventDefault();
    swal({   
        title: "¿Estás seguro?",   
        text: "¡Se eliminará el material y no podra recuperarlo!",   
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
                   tr.remove();
                   swal("¡Material Eliminado!", ".", "success");
               } else {
                   sweetAlert("Oops...", "¡Hubo un error al procesar la solicitud!", "error");
               }
           },
           error: function(error) {
                sweetAlert("Error...", error, "error");
           }
        });
    });
});