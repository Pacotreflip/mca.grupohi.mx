Vue.component('origenes-usuarios', {
    
    data: function() {
        return {
            usuarios: [],
            usuario: '',
            origenes:  []
        }
    },
    
    template: require('./templates/origenes-usuarios.html'),
    
    beforeCreate: function () {
        this.$http.get('usuarios').then((response) => {
            this.usuarios = response.body;
        }, (response) => {
            console.error(errors)
        });
    },
    
    methods: {
        fetchOrigenes: function() { 
            if(this.usuario) {
                this.$http.get(App.host + '/usuarios/' + this.usuario + '/origenes').then((response) => {
                    this.origenes = response.body;
                }, (response) => {
                    console.log(response);
                });
            } else {
               this.origenes = [];
            }
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
