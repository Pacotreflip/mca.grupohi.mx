if($('#viajes_netos_autorizar').length) {
    var auth_config = {
        auto_filter: true,
        watermark: [
            'Fecha Llegada', 
            'Hora Llegada', 
            'Camión', 
            'Tiro', 
            'Origen', 
            'Material', 
            'Observaciones'
        ],
        col_2: 'select',
        col_3: 'select',
        col_4: 'select',
        col_5: 'select',
        col_7: 'none',
        col_8: 'none',
        col_9: 'none',
        base_path: App.tablefilterBasePath,      
        col_types: [
            'string',            
            'string',
            'string',
            'string',
            'string',
            'string',
            'string',
            'none',
            'none',
            'none'
        ],
        paging: true,
        paging_length: 50,
        rows_counter: true,
        rows_counter_text: 'Viajes: ',
        btn_reset: true,
        btn_reset_text: 'Limpiar',
        clear_filter_text: 'Limpiar',
        loader: true,
        page_text: 'Pagina',
        of_text: 'de',
        help_instructions: false,
        extensions: [{ name: 'sort' }]       
    };
    var tf = new TableFilter('viajes_netos_autorizar', auth_config);
    tf.init();
    
    $("input:checkbox").click(function(e){
        if(this.checked) {
            var group = "input:checkbox[id='"+$(this).attr("id")+"']";
            $(group).prop("checked", false);
            $(this).prop("checked",true);
        }
    });   
}

$('#viaje_neto_update').submit(function(e) {
    e.preventDefault();
    var form = $(this);
    swal({   
        title: "¿Estás seguro?",   
        text: "Cambiaras es estado del viaje",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Si",  
        cancelButtonText: 'Cancelar',
        closeOnConfirm: false 
    }, function(){   
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                window.location = response.path;
            },
            error: function(error) {
                sweetAlert("Oops...", "Error Interno del Servidor!", "error");
            }
        });
    });
});