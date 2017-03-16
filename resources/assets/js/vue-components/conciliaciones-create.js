Vue.component('conciliaciones-create', {
    data: function() {
        return {
            'conciliacion' : {
                'idsindicato'    : '',
                'idempresa'      : '',
            },
            'form' : {
                'errors' : []
            },
        }
    },

    methods: {
        confirmarRegistro: function(e) {
            e.preventDefault();

            swal({
                title: "¿Desea continuar con el registro?",
                text: "¿Esta seguro de que la información es correcta?",
                type: "info",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
            }, () => this.registrar() );
        },

        registrar: function() {
            var _this = this;
            this.form.errors = [];
            this.$http.post(App.host + '/conciliaciones', this.conciliacion).then((response) => {
                var conciliacion = response.body.conciliacion;
                window.location.href = App.host + '/conciliaciones/' + conciliacion.idconciliacion + '/edit';
        }, (error) => {
                App.setErrorsOnForm(this.form, error.body);
            });
        }
    }
});