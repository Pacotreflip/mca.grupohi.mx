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
            guardando: false,
        }
    },
    
    template: require('./templates/fda-bancomaterial.html'),
    
    beforeCreate: function () {
        this.$http.get('materiales').then((response) => {
            this.materiales = response.body;
        }, (response) => {
            console.error(errors)
        });
        
        this.$http.get('origenes').then((response) => {
            this.bancos = response.body;
        }, (response) => {
            console.error(errors)
        });
        
        this.$http.get('factores_abundamiento_banco_material').then((response) => {
            this.factores = response.body;
        }, (response) => {
            console.error(errors)
        });
        
        
    },
    
    methods: {
        guardar: function() {
            
        },

        actualizar: function(factor) {
            
        },
        
        asignar: function(origen) {
            var _this = this;
            if(origen.estatus == 1 || origen.estatus == 2) {
                swal({   
                    title: "¿Desea continuar?",   
                    text: "¡La asignación del origen cambiara!",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Aceptar",   
                    cancelButtonText: "Cancelar",   
                    closeOnConfirm: false }, 
                function(){   
                    _this.$http.post(App.host + '/usuarios/' + _this.usuario + '/origenes/' + origen.id, {_token: App.csrfToken}).then((response) => {
                        _this.fetchOrigenes();
                        swal({   
                            title: "",  
                            text: "La asignación del origen ha cambiado",   
                            timer: 750,   
                            showConfirmButton: false ,
                            type: "success"
                        });
                    },(response) => {
                       console.log(response); 
                    });
                });
            }
        }
    }
});
