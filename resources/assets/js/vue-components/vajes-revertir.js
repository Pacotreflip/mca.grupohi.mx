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
                        'Fecha Llegada',
                        'Tiro',
                        'Camion',
                        'Cubic.',
                        'Material',
                        'Origen',
                        'Modificar'
                    ],
                    col_0: 'select',
                    col_1: 'select',
                    col_2: 'select',
                    col_4: 'select',
                    col_5: 'select',
                    col_6: 'none',

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
                var tf = new TableFilter('viajes_netos_modificar', val_config);
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

        revertir: function(id) {
            console.log(id);
        }
    }
});