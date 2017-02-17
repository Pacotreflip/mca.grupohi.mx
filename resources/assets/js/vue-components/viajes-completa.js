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

Vue.component('viajes-manual-completa', {
    
    data: function() {
        return {
            'generales': {
                'IdCamion': '',
                'IdOrigen': '',
                'IdTiro': '',
                'IdMaterial': ''
            },
            'form': {
                'viajes': [],
                'errors': []
            },
            'guardando': false,
            'cargando': false,
            'numViajes': ''
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
        
        setFechaLlegada: function(viaje, event) {
            viaje.FechaLlegada = event.currentTarget.value;
        },
        
        setCubicacion: function(viaje, event) {
            this.$http.get(App.host + '/camiones/' + event.currentTarget.value).then((response) => {
                viaje.Cubicacion = response.body.CubicacionParaPago;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        fillTable: function(e) {
            e.preventDefault();
            this.cargando = true;
            this.form.viajes = [];
            this.generales.IdCamion = '';
            this.generales.IdOrigen = '',
            this.generales.IdTiro = '';
            this.generales.IdMaterial = ''; 
            var i = 1;
            while(i <= this.numViajes) {
                this.addViaje(i);
                i++;
            }
            this.cargando = false;
        },
        addViaje: function(index) {
            this.form.viajes.push({
               'Id': index,
               'NumViajes': '',
               'FechaLlegada': timeStamp(1),
               'IdCamion': '',
               'Cubicacion': '',
               'IdOrigen': '',
               'IdTiro': '',
               'IdRuta': '',
               'IdMaterial': '',
               'PrimerKm': '',
               'KmSub': '',
               'KmAd': '',
               'Turno': 'M',
               'Observaciones': '',
               'Rutas': []
            });
        },

        fetchRutas: function(viaje) {
            if(viaje.IdOrigen && viaje.IdTiro) {
                this.$http.get(App.host + '/rutas', {'params': {'IdOrigen': viaje.IdOrigen, 'IdTiro': viaje.IdTiro}}).then((response) => {
                    viaje.Rutas = response.body;
                    if(viaje.Rutas.length) {
                        var Ruta = viaje.Rutas[0];
                        viaje.IdRuta = Ruta.IdRuta;
                    }
                }, (response) => {
                    App.setErrorsOnForm(this.form, response.body);
                })
            } else {
                viaje.IdRuta = "";
            }
        },
        
        fetchKms: function(viaje) {
            if(viaje.IdMaterial) {
                this.$http.get(App.host + '/tarifas_material', {'params': {'IdMaterial': viaje.IdMaterial}}).then((response) => {
                    viaje.PrimerKm = response.body.PrimerKM;
                    viaje.KmSub = response.body.KMSubsecuente;
                    viaje.KmAd = response.body.KMAdicional;
                }, (response) => {
                    App.setErrorsOnForm(this.form, response.body);
                });
            } else {
                viaje.PrimerKm = '';
                viaje.KmSub = '';
                viaje.KmAd = '';
            }
        },
        
        setCamionGeneral: function(event) {
            var _this = this;
            this.form.viajes.forEach(function(viaje) {
                viaje.IdCamion = event.currentTarget.value;
                _this.setCubicacion(viaje, event);
            });
        },
        
        setOrigenGeneral: function(event) {
            var _this = this;
            this.form.viajes.forEach(function(viaje) {
                viaje.IdOrigen = event.currentTarget.value;
                _this.fetchRutas(viaje);
            });
        },
        
        setTiroGeneral: function(event) {
            var _this = this;
            this.form.viajes.forEach(function(viaje) {
                viaje.IdTiro = event.currentTarget.value;
                _this.fetchRutas(viaje);
            });
        },
        
        setMaterialGeneral: function(event) {
            var _this = this;
            this.form.viajes.forEach(function(viaje) {
                viaje.IdMaterial = event.currentTarget.value;
                _this.fetchKms(viaje);
            });
        },
        
        confirmarCarga: function(e) {
            e.preventDefault();

            swal({
                title: "¿Desea continuar con el registro?", 
                text: "¿Esta seguro de que la información es correcta?", 
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            }, () => this.registrar() );
        },
        registrar: function() {
            this.guardando = true;
            this.form.errors = [];

            this.$http.post(App.host + '/viajes/netos/completa', this.form).then((response) => {
                swal('', response.body.message, 'success');
                this.guardando = false;
                if(!response.body.rechazados.length) {
                    this.form.viajes = [];
                }
            },(response) => {
                App.setErrorsOnForm(this.form, response.body);
                this.guardando = false;
            });
        }
    }
});