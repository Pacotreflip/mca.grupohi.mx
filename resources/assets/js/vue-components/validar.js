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

Vue.component('viajes-validar', {
    data: function() {
        return {
            'datosConsulta' : {
                'fechaInicial' : timeStamp(1),
                'fechaFinal' : timeStamp(1),
                'code' : '',
                'tipo' : ''
            },
            'viajes' : []
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
        }
    },
    
    methods: {
        setFechaInicial: function(event) {
            this.datosConsulta.fechaInicial = event.currentTarget.value;
        },
        
        setFechaFinal: function(event) {
            this.datosConsulta.fechaFinal = event.currentTarget.value;
        },
        
        fetchViajes: function(tipo) {
            if(tipo == 'dates') {
                if(!this.datosConsulta.fechaInicial || !this.datosConsulta.fechaFinal) {
                    swal('Por favor introduzca las fechas', '', 'warning');
                } else {
                    this.viajes = [];
                    this.$http.get(App.host + '/viajes/netos', {'params' : {'type' : 'dates', 'fechaInicial' : this.datosConsulta.fechaInicial, 'fechaFinal' : this.datosConsulta.fechaFinal}}).then((response) => {
                        this.viajes = response.body;
                    }, (error) => {
                        App.setErrorsOnForm(this.form, response.body);
                    });
                }
            } else {
                if(!this.datosConsulta.code) {
                    swal('Por favor introduzca el codigo del viaje', '', 'warning');
                } else {
                    this.viajes = [];
                    this.$http.get(App.host + '/viajes/netos', {'params' : {'type' : 'code', 'code' : this.datosConsulta.code}}).then((response) => {
                        this.viajes = response.body;
                    }, (error) => {
                        App.setErrorsOnForm(this.form, response.body);
                    });
                }
            } 
        }
    }
});