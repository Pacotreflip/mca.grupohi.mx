$(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
});
$('[data-submenu]').submenupicker();
$('.fecha').datepicker({
    format: 'yyyy-mm-dd',
    language: 'es',
    autoclose: true,
    clearBtn: true,
    todayHighlight: true
});

$(".element_destroy").off().on("click", function(event) {
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

$('.mac').keyup(function (e) {
    var r = /([a-f0-9]{2})/i;
    var str = e.target.value.replace(/[^a-f0-9:]/ig, "");
    if (e.keyCode != 8 && r.test(str.slice(-2))) {
        str = str.concat(':')
    }
    e.target.value = str.slice(0, 17);
});

$('.alfanum').on("keypress",function (e) {
var tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true;
    var patron =/^[0-9a-zA-Z]+$/;
    var te = String.fromCharCode(tecla);
    if(!patron.test(te))
        event.returnValue = false;

});