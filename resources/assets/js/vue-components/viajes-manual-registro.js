function initialState(index) {
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

    return {
        'Id': index,
        'FechaLlegada': date,
        'HoraLlegada': time,
        'IdCamion': '',
        'IdOrigen': '',
        'IdTiro': '',
        'IdMaterial': '',
        'Observaciones': ''
    }
}

Vue.component('viajes-manual-registro', {
    
    data: function() {
        return {
            'materiales': [],
            'origenes': [],
            'tiros': [],
            'camiones': [],
            'form': {
                'viajes': [initialState(1)],
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
    
    filters: {
        origen: function(value, IdOrigen) {
            console.log(value);
            return this.tiros.filter(function(tiro){
                var result = false;
                tiro.origenes.forEach(function(origen){
                    if(origen.IdOrigen == IdOrigen) {
                        result = true;
                    }
                });
                return result;
            });
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

        setFechaLlegada: function(viaje, event) {
            viaje.FechaLlegada = event.currentTarget.value;
        },
        
        addViaje: function() {
            this.numViajes++;
            this.form.viajes.push(initialState(this.numViajes));
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
                        timer: 1000,   
                        showConfirmButton: false
                    });
                    this.guardando = false;
                    this.form = initialState();
                    this.tiros = [];
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