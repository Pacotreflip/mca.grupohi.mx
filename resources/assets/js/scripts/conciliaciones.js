/**
 * Created by JFEsquivel on 16/03/2017.
 */
$('.cancelar_conciliacion').off().on('click', function(e) {
    e.preventDefault();

    var url = $(this).closest('form').attr('action');

    swal({
        title: "¡Cancelar Conciliación!",
        text: "¿Esta seguro de que deseas cancelar la conciliación?",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        inputPlaceholder: "Motivo de la cancelación.",
        confirmButtonText: "Si, Cancelar",
        cancelButtonText: "No",
        showLoaderOnConfirm: true

    },
    function(inputValue){
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("Escriba el motivo de la cancelación!");
            return false
        }
        $.ajax({
            url: url,
            type : 'POST',
            data : {
                _method : 'DELETE',
                motivo : inputValue
            },
            success: function(response) {
                if(response.status_code = 200) {
                    swal({
                        type: 'success',
                        title: '¡Hecho!',
                        text: 'Conciliación cancelada correctamente',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        closeOnConfirm: false
                    },
                    function () {
                        location.reload();
                    });
                }
            },
            error: function (error) {
                App.setErrorsOnForm(_this.form, error.responseText);
            }
        });
    });
});