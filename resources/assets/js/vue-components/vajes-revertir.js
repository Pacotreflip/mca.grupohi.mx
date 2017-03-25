Vue.component('viajes-revertir', {
    data : function() {
        return {
            'viajes' : [],
            'cargando' : false,
            'guardando' : false,
            'form' : {
                'errors' : []
            },
        }
    },

    directives: {
        datepicker: {
            inserted: function(el) {
                $(el).datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    endDate: '0d'
                });
                $(el).val(App.timeStamp(1));
            }
        },

        tablefilter: {
            inserted: function(el) {
                var val_config = {
                    auto_filter: true,
                    watermark: [
                        'Fecha de Llegada',
                        'Hora de Llegada',
                        'Origen',
                        'Tiro',
                        'Camión',
                        'Cubic.',
                        'Material',
                        'Código (Ticket)',
                        'Modificar'
                    ],
                    col_0: 'select',
                    col_2: 'select',
                    col_3: 'select',
                    col_4: 'select',
                    col_6: 'select',
                    col_8: 'none',

                    base_path: App.tablefilterBasePath,
                    paging: false,
                    rows_counter: false,
                    rows_counter_text: 'Viajes: ',
                    btn_reset: true,
                    btn_reset_text: 'Limpiar',
                    clear_filter_text: 'Limpiar',
                    loader: true,
                    help_instructions: false,
                    extensions: [{ name: 'sort' }]
                };
                var tf = new TableFilter('viajes_revertir', val_config);
                tf.init();
            }
        }
    },

    methods: {
        buscar: function(e) {
            e.preventDefault();

            var _this = this;
            this.form.errors = [];
            this.cargando = true;
            this.viajes = [];

            var data = $('.form_buscar').serialize();
            this.$http.get(App.host + '/viajes?tipo=revertir&' + data).then((response) => {
                _this.viajes = response.body.data;
                _this.cargando = false;

                if(!_this.viajes.length) {
                    swal({
                        type: 'warning',
                        title: '¡Sin Resultados!',
                        text: 'Ningún viaje coincide con los datos de consulta',
                        showConfirmButton: true
                    });
                }
            }, (error) => {
                _this.cargando = false;
                App.setErrorsOnForm(this.form, error.body);

            });
        },

        revertir: function(viaje) {

            var _this = this;
            var url = App.host + '/viajes/' + viaje.IdViaje;
            swal({
                    title: "¡Revertir Viaje!",
                    text: "¿Desea revertir el viaje?",
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Si, Revertir",
                    cancelButtonText: "No",
                    showLoaderOnConfirm: true
                },
                function(){
                    $.ajax({
                        url: url,
                        type : 'POST',
                        data : {
                            _method : 'PATCH',
                            action : 'revertir'
                        },
                        success: function(response) {
                            if(response.status_code = 200) {
                                swal({
                                        type: 'success',
                                        title: '¡Hecho!',
                                        text: 'Viaje Revertido Correctamente',
                                        showCancelButton: false,
                                        confirmButtonText: 'OK',
                                        closeOnConfirm: true
                                    },
                                    function () {
                                        viaje.Estatus = -1;
                                    });
                            }
                        },
                        error: function (error) {
                            swal({
                                type: 'error',
                                title: '¡Error!',
                                text: App.errorsToString(error.responseText)
                            });
                        }
                    });
                });
        },
    }
});