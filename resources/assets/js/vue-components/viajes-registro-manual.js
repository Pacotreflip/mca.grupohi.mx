Vue.component('viajes-registro-manual', {
    
    data: function() {
        return {
            'materiales': [],
            'origenes': [],
            'tiros': [],
            'camiones': [],
            'form': {
                'FechaLlegada': '',
                'HoraLlegada': '',
                'IdCamion': '',
                'IdOrigen': '',
                'IdTiro': '',
                'IdMaterial': '',
                'Observaciones': '',
                'errors': []
            },
            'guardando': false,
            'cargando': false
        };
    },
    
    created: function() {
        this.cargando = true;
        this.fetchMateriales();
        this.fetchOrigenes();
        this.fetchCamiones();
        this.cargando = false;
    },
    
    directives: { 
        datepicker: {
            inserted: function(el) {
                $(el).datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: false,
                    clearBtn: true,
                    todayHighlight: true
                });
            }
        }
    },
    
    methods: {
        setFechaLlegada: function(event) {
            this.form.FechaLlegada = event.currentTarget.value;
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
            if(this.form.IdOrigen) {
                this.$http.get(App.host + '/origenes/' + this.form.IdOrigen + '/tiros').then((response) => {
                    this.tiros = response.body;
                }, (response) => {
                    App.setErrorsOnForm(this.form, response.body);
                });
            }
        },
        
        fetchCamiones: function() {
            this.$http.get(App.host + '/camiones').then((response) => {
                this.camiones = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        guardar: function() {
            this.guardando = true;
            this.form.errors = [];
            this.$http.post(App.host + '/viajes/registro_manual', this.form).then((response) => {
                if(response.body.success) {
                    swal({
                        type: 'success',
                        title: '',
                        text: response.body.message,
                        timer: 1000,   
                        showConfirmButton: false
                    });
                    this.guardando = false;
                }
            }, (response) => {
                this.guardando = false;
                App.setErrorsOnForm(this.form, response.body);
            });
        }
    }
});