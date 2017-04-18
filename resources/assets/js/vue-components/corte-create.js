Vue.component('corte-create', {
    data: function() {
        return {
            viajes_netos : [],
            form : {
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
                    _this.viajes_netos = [];
                },
                success: function (response) {
                    _this.viajes_netos = response.viajes_netos;
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
                    _this.cargando = true;
                }
            });
        },

        confirmar_corte: function () {
            swal({
                title: "¿Desea continuar con el corte?",
                text: "¿Esta seguro de que la información es correcta?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            }, () => this.corte());
        },

        corte: function () {
            console.log('corte');
        }

    }
});