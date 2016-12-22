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

Array.prototype.removeValue = function(name, value){
   var array = $.map(this, function(v,i){
      return v[name] === value ? null : v;
   });
   this.length = 0; //clear original array
   this.push.apply(this, array); //push all elements except the one we want to delete
}

Vue.component('viajes-manual-registro', {
    
    data: function() {
        return {
            'materiales': [],
            'origenes': [],
            'tiros': [],
            'camiones': [],
            'form': {
                'viajes': [
                    {
                        'Id': 1,
                        'FechaLlegada': timeStamp(1),
                        'HoraLlegada': timeStamp(2),
                        'IdCamion': '',
                        'IdOrigen': '',
                        'IdTiro': '',
                        'IdMaterial': '',
                        'Observaciones': '',
                        'Tiros': []
                    }
                ],
                'errors': []
            },
            'guardando': false,
            'cargando': false,
            'numViajes': 1
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
        }
    },
 
    methods: {         
        initialize: function() {
            this.cargando = true;
            this.fetchMateriales();
            this.fetchOrigenes();
            this.fetchTiros();
            this.fetchCamiones();
            this.cargando = false;            
        },
        
        setTiros: function(viaje) {
            viaje.Tiros = [];
            if(viaje.IdOrigen) {
                this.tiros.forEach(function(tiro){
                    var result = false;
                    tiro.origenes.forEach(function(origen){
                        if(origen.IdOrigen == viaje.IdOrigen) {
                            result = true;
                        }
                    });
                    if(result) {
                        viaje.Tiros.push(tiro);
                    }
                })
            } else {
                viaje.IdTiro = '';
            }
        },

        setFechaLlegada: function(viaje, event) {
            viaje.FechaLlegada = event.currentTarget.value;
        },
        
        addViaje: function() {
            this.numViajes+=1;
            this.form.viajes.push({
                'Id': this.numViajes,
                'FechaLlegada': timeStamp(1),
                'HoraLlegada': timeStamp(2),
                'IdCamion': '',
                'IdOrigen': '',
                'IdTiro': '',
                'IdMaterial': '',
                'Observaciones': '',
                'Tiros': []
            });
        },
        
        removeViaje: function(viaje) {
            this.form.viajes.removeValue('Id', viaje.Id);
        },
       
        fetchMateriales: function() {
            this.$http.get(App.host + '/materiales').then((response) => {
                this.materiales = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        fetchOrigenes: function() {
            this.$http.get(App.host + '/origenes').then((response) => {
                this.origenes = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        fetchTiros: function() {
            this.$http.get(App.host + '/tiros').then((response) => {
                this.tiros = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        fetchCamiones: function() {
            this.$http.get(App.host + '/camiones').then((response) => {
                this.camiones = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        registrar: function() {
            this.guardando = true;
            this.form.errors = [];
            this.$http.post(App.host + '/viajes/manual', this.form).then((response) => {
                if(response.body.success) {
                    swal({
                        type: 'success',
                        title: '',
                        text: response.body.message,
                        showConfirmButton: true
                    });
                    this.guardando = false;
                }
            }, (response) => {
                this.guardando = false;
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        confirmarRegistro: function (e) {
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
    }
});