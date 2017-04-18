Vue.component('viajes-index', {
    data: function() {
        return {
            'viajes_netos' : [],
            'cargando' : false,
            'form' : {
                'errors' : []
            },
        }
    },

    directives: {
        datepicker: {
            inserted: function(el) {
                $(el).datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'es',
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    endDate: '0d'
                });
                $(el).val(App.timeStamp(1));
            }
        },

        select2: {
            inserted: function (el) {
                $(el).select2({
                    placeholder: "--SELECCIONE--",
                });
            }
        }
    },

    methods: {
        buscar: function(e) {
            e.preventDefault(

            );
            var _this = this;

            var data = $('.form_buscar').serialize();
            var url = App.host + '/viajes_netos?action=index';

            $.ajax({
                type : 'GET',
                url  : url,
                data : data,
                beforeSend : function () {
                    _this.cargando = true;
                    _this.viajes_netos = [];
                    _this.form.errors = [];
                    $('#partials_errors').empty();
                },
                success:function (response) {
                    if(! response.viajes_netos.length) {
                        swal('¡Sin Resultados!', 'Ningún viaje coincide con los datos de consulta', 'warning');
                    } else {
                        _this.viajes_netos = response.viajes_netos;
                    }
                },
                error: function (error) {
                    if(error.status == 422) {
                        App.setErrorsOnForm(_this.form, error.responseJSON);
                    } else if(error.status == 500) {
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

        pdf: function(e) {
            e.preventDefault();

            var url = App.host + '/pdf/viajes_netos';

            $('.form_buscar').attr('action', url);
            $('.form_buscar').attr('method', 'GET');
            $('.form_buscar').submit();
        }
    }
});