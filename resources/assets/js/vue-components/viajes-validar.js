// register modal component
Vue.component('modal-validar', {
  template: '#modal-template'
});

Vue.component('viajes-validar', {
    data: function() {
        return {
            'viajes_netos' : [],
            'cargando' : false,
            'guardando' : false,
            'form' : {
                'data' : {
                    'Accion' : '',
                    'IdSindicato' : '',
                    'IdEmpresa' : '',
                    'TipoTarifa' : '',
                    'TipoFDA' : '',
                    'Tara' : '',
                    'Bruto' : '',
                    'Cubicacion' : ''
                },
                'errors' : []
            },
        }
    },

    /*computed: {
        getViajesByCode: function() {
            var _this = this;
            var search = RegExp(_this.datosConsulta.code);
            return _this.viajes.filter(function(viaje) {
            if(!viaje.Code.length && !_this.datosConsulta.code.length ) {
                return true;
            } else if (viaje.Code && (viaje.Code).match(search)) {
              return true;
            }
            return false;
          });
        }
    },*/

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

            e.preventDefault();

            var _this = this;

            this.cargando = true;
            this.form.errors = [];

            var data = $('.form_buscar').serialize();
            var url = App.host + '/viajes/netos?action=validar&' + data;

            this.$http.get(url).then((response) => {
                _this.cargando = false;
                if(! response.body.viajes_netos.length) {
                    swal('¡Sin Resultados!', 'Ningún viaje coincide con los datos de consulta', 'warning');
                } else {
                    _this.viajes_netos = response.body.viajes_netos;
                }
            }, (error) => {
                _this.cargando = false;
                swal('¡Error!', App.errorsToString(error.body), 'error');
            });
        },

        validar: function(viaje) {

            var _this = this;

            swal({
                title: "¿Desea continuar con la validación?",
                text: "¿Esta seguro de que la información es correcta?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            },
            function () {
                _this.guardando = true;
                _this.form.errors = [];
                var data = _this.form.data;

                _this.$http.post(App.host + '/viajes/netos', {'type' : 'validar', '_method' : 'PATCH', 'IdViajeNeto' : viaje.IdViajeNeto,  data}).then((response) => {
                    swal({
                        type: response.body.tipo,
                        title: '',
                        text: response.body.message,
                        showConfirmButton: true
                    });

                    if(response.body.tipo == 'success' || response.body.tipo == 'info') {
                       viaje.ShowModal = false;
                        _this.viajes_netos.splice(_this.viajes_netos.indexOf(viaje), 1);
                    }

                    _this.guardando = false;
                }, (error) => {
                    _this.guardando = false;
                    viaje.ShowModal = false;
                    swal('¡Error!', App.errorsToString(error.body), 'error');
                });
            });
        },

        itemClass: function(index) {
            if(index == 0){
                return 'item active';
            } else {
                return 'item';
            }
        },

        showModal: function(viaje) {
            viaje.ShowModal = true;
            this.initializeData(viaje);
        },

        initializeData: function(viaje) {

            this.form.data.Accion = viaje.Accion;
            this.form.data.IdSindicato = viaje.IdSindicato;
            this.form.data.IdEmpresa = viaje.IdEmpresa;
            this.form.data.TipoTarifa = viaje.TipoTarifa;
            this.form.data.TipoFDA = viaje.TipoFDA;
            this.form.data.Tara = viaje.Tara;
            this.form.data.Bruto = viaje.Bruto;
            this.form.data.Cubicacion = viaje.Cubicacion;
        }
    }
});