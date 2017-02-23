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
Vue.component('modal-modificar', {
  template: '#modal-template'
});

Vue.component('viajes-modificar', {
    data : function() {
        return {
            'datosConsulta' : {
                'fechaInicial' : timeStamp(1),
                'fechaFinal' : timeStamp(1)
            },
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
                this.$http.get(App.host + '/viajes/netos', {'params' : {'action' : 'modificar', 'fechaInicial' : this.datosConsulta.fechaInicial, 'fechaFinal' : this.datosConsulta.fechaFinal}}).then((response) => {
                    this.viajes = response.body;                    
                    this.cargando = false;
                }, (error) => {
                    App.setErrorsOnForm(this.form, error.body);
                    this.cargando = false;
                });
            }
        },
        
        confirmarModificacion: function(index) {
            swal({
                title: "¿Desea continuar con la modificación?", 
                text: "¿Esta seguro de que la información es correcta?", 
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            }, () => this.modificar(index));
        },
        
        modificar: function(index) {
            var _this = this;
            this.guardando = true;
            this.form.errors = [];
            var viaje = this.viajes[index];
            this.$http.post(App.host + '/viajes/netos/modificar', {'_method' : 'PATCH',  viaje}).then((response) => {
                swal({
                    type: response.body.tipo,
                    title: '',
                    text: response.body.message,
                    showConfirmButton: true
                });
                _this.viajes[index].Camion = response.body.viaje.Camion;
                _this.viajes[index].IdCamion = response.body.viaje.IdCamion;
                _this.viajes[index].Cubicacion = response.body.viaje.Cubicacion;
                _this.viajes[index].Tiro = response.body.viaje.Tiro;
                _this.viajes[index].IdTiro = response.body.viaje.IdTiro;
                _this.viajes[index].Origen = response.body.viaje.Origen;
                _this.viajes[index].IdOrigen = response.body.viaje.IdOrigen;
                _this.viajes[index].Material = response.body.viaje.Material;
                _this.viajes[index].IdMaterial = response.body.viaje.IdMaterial;
          
                _this.viajes[index].ShowModal = false;      
                _this.guardando = false;
            }, (response) => {
                _this.guardando = false;
                _this.viajes[index].ShowModal = false;
                App.setErrorsOnForm(_this.form, response.body);
            });
        },
    }
});