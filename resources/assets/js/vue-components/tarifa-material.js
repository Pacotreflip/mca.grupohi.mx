Vue.component('tarifa-material', {
    data: function() {
        return {
            factores: [],
            materiales: [],
            factor: {
                IdMaterial: '',
                FactorAbundamiento: ''
            },
            form: {
                errors: []
            },
            guardando: false
        }
    },
    
    created: function() {
        this.fetchMateriales();
        this.fetchFactores();
        
    },
    
    methods: {
        fetchMateriales: function() {
            this.$http.get('materiales').then((response) => {
                this.materiales = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        fetchFactores: function() {
            this.$http.get('fda_material').then((response) => {
                this.factores = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        guardar: function() {
            this.guardando = true;
            this.form.errors = [];
            this.$http.post('fda_material', this.factor).then((response) => {
                if(response.body.success) {
                    swal({
                        type: 'success',
                        title: '',
                        text: response.body.message,
                        timer: 1000,
                        showConfirmButton: false
                    });
                    this.fetchFactores();
                    this.guardando = false;
                }
            }, (response) => {
                this.guardando = false;
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        actualizar: function(factor) {
            factor.guardando = true;
            this.form.errores = [];
            this.$http.post('fda_material', factor).then((response) => {
                if(response.body.success) {
                    swal({
                        type: 'success',
                        title: '',
                        text: response.body.message,
                        timer: 1000,   
                        showConfirmButton: false
                    });
                }
                factor.guardando = false;
            }, (response) =>  {
                factor.guardando = false;
                App.setErrorsOnForm(this.form, response.body);
            });
        }
    }
});