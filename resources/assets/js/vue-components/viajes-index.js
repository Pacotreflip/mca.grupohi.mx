Vue.component('viajes-index', {
    data: function() {
        return {
            'viajes_netos' : [],
            'cargando' : false,
            'form' : {
                'tipo'   : '2',
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
                        'Código', 
                        'Fecha Llegada', 
                        'Hora Llegada',
                        'Tiro', 
                        'Camion', 
                        'Origen', 
                        'Material', 
                        'Tiempo',
                        'Ruta',
                        'Distancia',
                        '1er Km',
                        'Km Sub.',
                        'Km Adc.',
                        'Importe',
                        '?',
                        'Validar'
                    ],
                    col_1: 'select',
                    col_3: 'select',
                    col_4: 'select',
                    col_5: 'select',
                    col_6: 'select',
                    col_8: 'select',
                    col_10: 'none',
                    col_11: 'none',
                    col_12: 'none',
                    col_14: 'none',
                    col_15: 'none',
                    col_16: 'none',
                    
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
                var tf = new TableFilter('viajes_netos_validar', val_config);
                tf.init();
            }         
        }        
    },

    methods: {
        buscar: function(e) {
            e.preventDefault(

            );
            var _this = this;

            var data = $('.form_buscar').serialize();
            var url = App.host + '/viajes_netos?action=index';

            $.ajax({
                type : 'GET',
                url  : url,
                data : data,
                beforeSend : function () {
                    _this.cargando = true;
                    _this.viajes_netos = [];
                    _this.form.errors = [];
                },
                success:function (response) {
                    if(! response.viajes_netos.length) {
                        swal('¡Sin Resultados!', 'Ningún viaje coincide con los datos de consulta', 'warning');
                    } else {
                        _this.viajes_netos = response.viajes_netos;
                    }
                },
                error: function (error) {
                    if(error.status == 422) {
                        App.setErrorsOnForm(_this.form, error.responseJSON);
                    } else if(error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                },
                complete: function () {
                    _this.cargando = false;
                }
            });
        },

        pdf: function(e) {
            e.preventDefault();

            var url = App.host + '/pdf/viajes_netos';

            $('.form_buscar').attr('action', url);
            $('.form_buscar').attr('method', 'GET');
            $('.form_buscar').submit();
        }
    }
});