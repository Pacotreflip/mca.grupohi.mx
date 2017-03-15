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


Vue.component('conciliaciones-edit', {
    data: function() {
        return {
            'tipo'         : '',
            'resultados'   : [],
            'conciliacion' : {
                'detalles' : []
            },
            'form' : {
                'errors' : []
            },
        }
    },

    directives: {
        datepicker: {
            inserted: function (el) {
                $(el).datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    endDate: '0d'
                });
            }
        },
    },

    created: function () {
        this.fetchDetalles();
    },

    methods: {

        fetchDetalles: function() {
            var _this = this;
            var url = $('.form_registrar').attr('action');

            this.$http.get(url).then(response => {
                _this.conciliacion.detalles = response.body.detalles;
            }, error => {
                App.setErrorsOnForm(_this.form, error.body);
            });
        },

        confirmarRegistro: function(e) {
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
            var _this = this;
            this.form.errors = [];
            this.guardando = true;

            var url = $('.form_registrar').attr('action');
            var data = $('.form_registrar').serialize();

            $.ajax({
                url: url,
                type: "POST",
                data: data,
                success: function (data)
                {
                    $('#resultados').modal('hide');
                    _this.resultados = [];

                    swal({
                        type: 'success',
                        title: '¡VIAJES ASIGNADOS CORRECTAMENTE!',
                        text: data.registros + ' Viajes asignado s a la conciliación',
                        showConfirmButton: true
                    });

                    _this.conciliacion.detalles = data.detalles;
                },
                error: function(error) {
                    App.setErrorsOnForm(_this.form, error.responseText);
                }
            });
        },

        agregar: function(e) {
            e.preventDefault();
        },

        buscar: function(e) {
            e.preventDefault();

            var _this = this;
            this.form.errors = [];
            this.cargando = true;

            var data = $('.form_buscar').serialize();
            this.$http.get(App.host + '/viajes?' + data).then((response) => {
                _this.resultados = response.body.data;
            $('#resultados').modal('show');
        }, (error) => {
                App.setErrorsOnForm(this.form, error.body);
            });
            this.cargando = false;
        }
    }
});