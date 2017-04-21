Vue.component('corte-create', {
    data: function() {
        return {
            viajes_netos : [],
            form : {
                datos: {},
                errors : []
            },
            cargando : false,
            guardando : false
        }
    },

    computed: {
        manuales: function () {
            return this.viajes_netos.filter(function (item) {
                return item.estatus === 20 || item.estatus === 21 || item.estatus === 22 || item.estatus === 29;
            });
        },
        moviles: function () {
            return this.viajes_netos.filter(function (item) {
                return item.estatus === 0 || item.estatus === 1;
            });
        }
    }
    ,
    directives: {
        datepicker : {
            inserted: function (el) {
                $(el).datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    endDate: '0d',
                    startDate: '-1d'
                });

                $(el).val(App.timeStamp(1));
            }
        },
        select2: {
            inserted: function (el) {
                $(el).select2({
                    placeholder: "--SELECCIONE--",
                    closeOnSelect: false
                });
            }
        },
    },
    methods: {
        buscar: function (e) {
            e.preventDefault();

            var _this = this;
            var data = $('#form_buscar').serialize();
            var url = App.host + '/viajes_netos?action=corte';

            $.ajax({
                type: 'GET',
                url: url,
                data: data,
                beforeSend: function () {
                    _this.cargando = true;
                    _this.form.errors = [];
                },
                success: function (response) {
                    _this.viajes_netos = response.viajes_netos;
                    if (! response.viajes_netos.length) {
                        swal({
                            type: 'warning',
                            title: '¡Sin Resultados!',
                            text: 'No hay viajes disponibles para las fechas solicitadas',
                            showConfirmButton: true
                        });
                    }
                    _this.form.datos = $('#form_buscar').serialize();
                },
                error: function (error) {
                    if (error.status == 422) {
                        App.setErrorsOnForm(_this.form, error.responseJSON);
                    } else if (error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                },
                complete: function () {
                    _this.cargando = false;
                }
            });
        },

        confirmar_inicio: function (e) {
            e.preventDefault();

            var _this = this;
            var url = App.host + '/viajes_netos/create?action=manual';

            swal({
                title: "¿Deseas dar inicio al corte?",
                text: "Antes de crear el corte por favor registre los viajes manuales correspondientes al día del corte<br><br>" +
                "Viajes manuales registrados: <strong>"+_this.manuales.length+"</strong><br><br>" +
                "<div class='text-center'><a href='"+url+"'>->> IR AL REGISTRO MANUAL DE VIAJES <<-</a></div>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, Iniciar",
                cancelButtonText: "No, cancelar",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                closeOnCancel: true,
                html: true
            },
            function(isConfirm){
                if (isConfirm) {
                    _this.iniciar();
                }
            });
        },

        iniciar: function () {
            var _this = this;
            var url = App.host + '/corte';
            var data = _this.form.datos;

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                beforeSend: function () {
                    _this.guardando = true;
                    _this.form.errors = [];
                },
                success: function (response) {
                    _this.viajes_netos = [];
                    window.location = response.path;
                },
                error: function (error) {
                    if (error.status == 422) {
                        App.setErrorsOnForm(_this.form, error.responseJSON);
                    } else if (error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                },
                complete: function () {
                    _this.guardando = false;
                }
            });
        },

        formato: function (val) {
            return numeral(val).format('0,0.00');
        }
    }
});