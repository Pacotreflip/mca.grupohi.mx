$(document).ready(function(){
    if($('.tarifa_create').length) {
        $('.tarifa_create').submit(function(event){
            var form = $(this);
            event.preventDefault();
            var data = $(this).serializeArray();
            data.push({_token: App.csrfToken});
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: data,
                success: function(response) {
                    $(form).attr('action', response.updateUrl);
                    $(form).append('<input name="_method" type="hidden" value="PATCH">');
                    $('.success').hide(100);
                    $('.success').html('<div class="alert alert-success">\n\
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n\
                        '+ response.message +'');
                    $('.success').show(100);
                    $('.errores').empty();
                },
                error: function(xhr, responseText, thrownError) {
                    $('.success').empty();
                    var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
                     if(ind1 === -1){
                         var salida = '<div class="alert alert-danger" role="alert"><ul>';
                         $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                             salida += '<li>'+elem+'</li>';
                         });
                         salida += '</ul></div>';
                         $(".errores").html(salida);
                     }else{
                         var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                         var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
                         var cad1 = xhr.responseText.substring(ind1);
                         var ind2 = cad1.indexOf('</span>');
                         var cad2 = cad1.substring(32,ind2);
                         if(cad2 !== ""){
                             salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
                         }else{
                             salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
                         }
                         salida += '</ul></div>';
                         $(".errores").html(salida);
                     }
                }
            });
        });
    }
});

