Vue.component('fda-bancomaterial', {
    
    data: function() {
        return {
            factores: [],
            materiales: [],
            bancos: [],
            factor: {
                IdMaterial: '',
                IdBanco: '',
                FactorAbundamiento: ''
            },
            form:{
                errors: []
            },
            guardando: false,
        }
    },
        
    created: function () {
        this.fetchBancos();
        this.fetchMateriales();
        this.fetchFactores();
    },
    
    methods: {
        fetchBancos: function () {
            this.$http.get('origenes').then((response) => {
                this.bancos = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        fetchMateriales: function() {
            this.$http.get('materiales').then((response) => {
                this.materiales = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });
        },
        
        fetchFactores: function() {
            this.$http.get('fda_banco_material').then((response) => {
                this.factores = response.body;
            }, (response) => {
                App.setErrorsOnForm(this.form, response.body);
            });  
        },
        
        guardar: function () {
            this.guardando = true;
            this.form.errors = [];
            this.$http.post('fda_banco_material', this.factor).then((response) => {
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

        actualizar: function (factor) {
            factor.guardando = true;
            this.form.errors = [];
            this.$http.post('fda_banco_material', factor).then((response) => {
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
            }, (response) => {
                factor.guardando = false;
                App.setErrorsOnForm(this.form, response.body);
            });
        },
    }
});
