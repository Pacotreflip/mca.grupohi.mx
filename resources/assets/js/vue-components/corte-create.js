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
        timepicker: {
            inserted: function (el) {
                $(el).timepicker({
                    'timeFormat': 'hh:mm:ss a',
                    'showDuration': true,
                });
                if($(el).hasClass('time') && $(el).hasClass('start')) {
                    $(el).val('12:00:00 am');
                }
                if($(el).hasClass('time') && $(el).hasClass('end')) {
                    $(el).val('11:59:59 pm');
                }
            }
        }
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
                },
                success: function (response) {
                    if (! response.viajes_netos.length) {
                        swal({
                            type: 'warning',
                            title: '¡Sin Resultados!',
                            text: 'Ningún viaje coincide con los datos de consulta',
                            showConfirmButton: true
                        });
                    }

                    _this.viajes_netos = response.viajes_netos;

                    $.each($('#form_buscar').serializeArray(), function () {
                        _this.form.datos[this.name] = this.value;
                    });
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

        confirmar_corte: function (e) {
            e.preventDefault();

            var _this = this;

            swal({
                title: "¿Desea continuar con el corte?",
                text: "A continuación escriba el motivo del corte",
                type: "input",
                showCancelButton: true,
                inputPlaceholder: "Motivo del Corte",
                confirmButtonText: "Si",
                cancelButtonText: "No",
                closeOnConfirm: false,
                confirmButtonColor: "#ec6c62"
            }, function(inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("¡Escriba el Motivo del Corte!");
                    return false
                }
                swal({
                    title: "",
                    text: "",
                    timer: 250,
                    showConfirmButton: false,
                    type: 'success'
                });
                _this.corte(inputValue);
            });
        },

        corte: function (inputValue) {
            var _this = this;
            var url = App.host + '/corte';
            _this.form.datos['motivo'] = inputValue;
            var data = _this.form.datos;

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                beforeSend: function () {
                    _this.guardando = true;
                },
                success: function (response) {
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