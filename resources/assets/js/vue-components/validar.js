function timeStamp(type) {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    var hh = today.getHours();
    var min = today.getMinutes();

    if(dd < 10) {dd = '0' + dd} 
    if(mm < 10) {mm = '0' + mm} 
    if(hh < 10) {hh = '0' + hh}
    if(min < 10) {min = '0' + min}

    var date = yyyy + '-' + mm + '-' + dd;
    var time = hh + ":" + min;

    return type == 1 ? date : time;
}
// register modal component
Vue.component('modal-validar', {
  template: '#modal-template'
});

Vue.component('viajes-validar', {
    data: function() {
        return {
            'datosConsulta' : {
                'fechaInicial' : timeStamp(1),
                'fechaFinal' : timeStamp(1),
                'code' : '',
                'tipo' : ''
            },
            'viajes' : [],
            'empresas' : [],
            'sindicatos' : [],
            'cargando' : false,
            'guardando' : false,
            'form' : {
                'errors' : []
            },
        }
    },
    
    computed: {
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
    },
    
    created: function() {
        this.initialize();
    },
    
    directives: { 
        datepicker: {
            inserted: function(el) {
                $(el).datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: false,
                    clearBtn: true,
                    todayHighlight: true,
                    endDate: '0d'
                });
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
                    
                    base_path: App.tablefilterBasePath,
                    paging: false,
                    rows_counter: true,
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
        initialize: function() {
            this.cargando = true;
            this.fetchEmpresas();
            this.fetchSindicatos();
            this.cargando = false;
        },

        setFechaInicial: function(event) {
            this.datosConsulta.fechaInicial = event.currentTarget.value;
        },
        
        setFechaFinal: function(event) {
            this.datosConsulta.fechaFinal = event.currentTarget.value;
        },
        
        fetchViajes: function() {
            if(!this.datosConsulta.fechaInicial || !this.datosConsulta.fechaFinal) {
                swal('', 'Por favor introduzca el rango de fechas a consultar', 'warning');
            } else {
                this.viajes = [];
                this.cargando = true;
                this.form.errors = [];
                this.$http.get(App.host + '/viajes/netos', {'params' : {'fechaInicial' : this.datosConsulta.fechaInicial, 'fechaFinal' : this.datosConsulta.fechaFinal}}).then((response) => {
                    this.viajes = response.body;                    
                    this.cargando = false;
                }, (error) => {
                    App.setErrorsOnForm(this.form, error.body);
                    this.cargando = false;
                });
            }
        },
        fetchEmpresas: function() {
            this.cargando = true;
            this.$http.get(App.host + '/empresas').then((response) => {
                this.empresas = response.body;
                this.cargando = false;
            }, (error) => {
                App.setErrorsOnForm(this.form, error.body);
                this.cargando = false;
            });
        },
        
        fetchSindicatos: function() {
            this.cargando = true;
            this.$http.get(App.host + '/sindicatos').then((response) => {
                this.sindicatos = response.body;
                this.cargando = false;
            }, (error) => {
                App.setErrorsOnForm(this.form, error.body);
                this.cargando = false;
            });
        },
        
        confirmarValidacion: function (index) {
                        
            swal({
                title: "¿Desea continuar con la validación?", 
                text: "¿Esta seguro de que la información es correcta?", 
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            }, () => this.validar(index));
        },
        
        validar: function(index) {
            var _this = this;
            this.guardando = true;
            this.form.errors = [];
            var viaje = this.viajes[index];
            this.$http.post(App.host + '/viajes/netos/validar', {'_method' : 'PATCH',  viaje}).then((response) => {
                swal({
                    type: response.body.tipo,
                    title: '',
                    text: response.body.message,
                    showConfirmButton: true
                });
                
                if(response.body.tipo == 'success' || response.body.tipo == 'info') {
                    _this.viajes[index].ShowModal = false;
                    _this.viajes.splice(index, 1);
                } 
                
                _this.guardando = false;
            }, (response) => {
                _this.guardando = false;
                _this.viajes[index].ShowModal = false;
                App.setErrorsOnForm(_this.form, response.body);
            });
        }
    }
});