Vue.component('viajes-index', {
    data: function() {
        return {
            'viajes_netos' : [],
            'conflicto' : [],
            'viaje_neto_seleccionado':"",
            'id_conflicto':"",
            'cargando' : false,
            'guardando' :false,
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
                    closeOnSelect: false
                });
            }
        },
        
        modal_conflicto:{
            //data-toggle="modal" data-target="#detalles_conflicto"
        }
    },

    methods: {
        buscar_en_conflicto: function(e){
            e.preventDefault(

            );
            var _this = this;

            var data = $('.form_buscar_en_conflicto').serialize();
            var url = App.host + '/viajes_netos?action=en_conflicto';

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
        buscar: function(e) {
            e.preventDefault();
            $('input[name=type]').val('');
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
        
        fetchDetalleConflicto: function(id_conflicto, id_viaje) {
            //console.log('fetchDetalle',id_conflicto);
            this.fetching = true;
            var _this = this;
            _this.viaje_neto_seleccionado = id_viaje;
            _this.id_conflicto = id_conflicto;
 //           console.log(_this.viaje_neto_seleccionado);
//            var url = $('#id_conciliacion').val();
            this.$http.get('viajes_netos?action=detalle_conflicto&id_conflicto='+id_conflicto+'&id_viaje='+id_viaje).then(response => {
                _this.conflicto = response.body;
                this.fetching = false;
            }, error => {
                this.fetching = false;
                App.setErrorsOnForm(_this.form, error.body);
            });
        },
        confirmarPonerPagable: function(e) {
            e.preventDefault();

            swal({
                title: "¿Desea continuar?",
                text: "¿Esta seguro de permitir que el viaje sea pagado?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            }, () => this.PonerPagable() );
        },
        PonerPagable: function () {
            var _this = this;
            var url = $('.form_pagable').attr('action');
            var data = $('.form_pagable').serialize()+'&IdViajeNeto='+_this.viaje_neto_seleccionado+'&IdConflicto='+_this.id_conflicto;
            var idviaje_neto = _this.viaje_neto_seleccionado;
            $.ajax({
                url: url,
                data: data,
                type: 'PATCH',
                beforeSend: function () {
                    _this.guardando = true;
                },
                success: function (response) {
                    swal('¡Hecho!', 'Datos actualizados correctamente', 'success');
                },
                error:function(error) {
                    if(error.status == 422) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseJSON)
                        });
                    } else if(error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                        //_this.fetchConciliacion();
                    }
                },
                complete: function () {
                    _this.guardando = false;
                    $('#detalles_conflicto').modal('hide');
                }
            });
        },
        
        detalle_conflicto: function(id_conflicto,id_viaje){

            this.fetchDetalleConflicto(id_conflicto,id_viaje);
           $("#detalles_conflicto").modal("show");
        },
        
        detalle_conflicto_pagable: function(id_conflicto,id_viaje){

            this.fetchDetalleConflicto(id_conflicto,id_viaje);
           $("#detalles_conflicto_pagable").modal("show");
        },

        pdf: function(e) {
            e.preventDefault();

            var url = App.host + '/pdf/viajes_netos';

            $('.form_buscar').attr('action', url);
            $('.form_buscar').attr('target', '_blank');
            $('.form_buscar').attr('method', 'GET');
            $('.form_buscar').submit();
        },
        pdf_conflicto: function(e) {
            e.preventDefault();

            var url = App.host + '/pdf/viajes_netos_conflicto';

            $('.form_buscar_en_conflicto').attr('action', url);
            $('.form_buscar_en_conflicto').attr('target', '_blank');
            $('.form_buscar_en_conflicto').attr('method', 'GET');
            $('.form_buscar_en_conflicto').submit();
        },

        excel: function (e) {
            e.preventDefault();
            var url = App.host + '/viajes_netos';

            $('input[name=type]').val('excel');
            $('.form_buscar').attr('action', url);
            $('.form_buscar').attr('method', 'GET');
            $('.form_buscar').submit();
        }
    }
});