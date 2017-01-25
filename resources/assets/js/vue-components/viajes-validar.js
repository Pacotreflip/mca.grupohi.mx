Vue.component('viajes-validar', { 
    data: function() {
        return {
        	'fechas': {
        		'FechaInicial' : '',
        		'FechaFinal': ''
        	},
            'form': {
            	'viajes': [],
                'errors': []
            },    
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
            this.cargando = false;            
        },
        
        setFechaInicial: function(event) {
            this.fechas.FechaInicial = event.currentTarget.value;
        },

        setFechaFinal: function(event) {
            this.fechas.FechaFinal = event.currentTarget.value;
        },

        fetchViajes: function(e) {
            e.preventDefault();

            this.cargando = true;
            console.log('cargando');
            this.form.viajes = [];
            this.$http.get(App.host + '/viajes/netos', {'params' : {'type' : 'validar', 'fechas' : this.fechas}}).then((response) => {
                    this.form.viajes = response.body;
                    this.cargando = false;
                    console.log('fin');
            }, (response) => {
            App.setErrorsOnForm(this.form, response.body);
                    this.cargando = false;
            });
        }       
    }
});