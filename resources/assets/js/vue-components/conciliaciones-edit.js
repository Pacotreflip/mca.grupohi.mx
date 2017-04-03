Vue.component('conciliaciones-edit', {
    data: function() {
        return {
            'tipo'         : '',
            'resultados'   : [],
            'conciliacion' : {
                'id'       : '',
                'detalles' : []
            },
            'form' : {
                'errors' : []
            },
            'guardando'  : false,
            'fetching'   : false
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
                $(el).val(App.timeStamp(1));
            }
        },

        fileinput: {
            inserted: function (el) {
                $(el).fileinput({
                    language: 'es',
                    theme: 'fa',
                    showPreview: false,
                    showUpload: true,
                    uploadAsync: true,
                    maxFileConut: 1,
                    autoReplate: true,
                    allowedFileExtensions: ['xls', 'xml', 'csv', 'xlsx'],
                    layoutTemplates: {
                        actionUpload: '',
                        actionDelete: '',
                    }
                });
            }
        }
    },

    created: function () {
        this.fetchConciliacion();
    },

    computed: {
        cancelados: function() {
            var _this = this;
            return _this.conciliacion.detalles.filter(function(detalle) {
                if(detalle.estado === -1) {
                    return true;
                }
                return false;
            });
        },

        conciliados: function () {
            var _this = this;
            return _this.conciliacion.detalles.filter(function (detalle) {
                if(detalle.estado === 1) {
                    return true;
                }
                return false;
            });
        },

        manuales: function() {
            var _this = this;
            return _this.conciliacion.detalles.filter(function (detalle) {
                return (detalle.estatus_viaje === 20 && detalle.estado === 1);
            });
        },

        moviles: function() {
            var _this = this;
            return _this.conciliacion.detalles.filter(function (detalle) {
                return (detalle.estatus_viaje === 0 && detalle.estado === 1);
            });
        }
    },

    methods: {

        fetchConciliacion: function() {
            this.fetching = true;
            var _this = this;
            var url = $('#id_conciliacion').val();
            this.$http.get(url).then(response => {
                _this.conciliacion = response.body.conciliacion;
                this.fetching = false;
            }, error => {
                this.fetching = false;
                App.setErrorsOnForm(_this.form, error.body);
            });
        },

        confirmarRegistro: function(e) {
            e.preventDefault();

            swal({
                title: "¿Desea continuar con la conciliación?",
                text: "¿Esta seguro de que la información es correcta?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            }, () => this.registrar() );
        },

        cancelar: function(e) {
            e.preventDefault();
            var _this = this;
            var url = $(e.target).attr('href');
            swal({
                title: "¡Cancelar Conciliación!",
                text: "¿Esta seguro de que deseas cancelar la conciliación?",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Motivo de la cancelación.",
                confirmButtonText: "Si, Cancelar",
                cancelButtonText: "No",
                showLoaderOnConfirm: true

            },
            function(inputValue){
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("Escriba el motivo de la cancelación!");
                    return false
                }
                $.ajax({
                    url: url,
                    type : 'POST',
                    data : {
                        _method : 'DELETE',
                        motivo : inputValue
                    },
                    success: function(response) {
                        if(response.status_code = 200) {
                            swal({
                                type: 'success',
                                title: '¡Hecho!',
                                text: 'Conciliación cancelada correctamente',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                closeOnConfirm: true
                            },
                            function () {
                                _this.fetchConciliacion();
                            });
                        }
                    },
                    error: function (error) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                        _this.fetchConciliacion();
                    }
                });
            });
        },

        cerrar: function(e) {
            e.preventDefault();

            var _this = this;
            var url = App.host + '/conciliaciones/' + _this.conciliacion.id;

            if(! this.conciliados.length) {
                swal({
                    type: 'warning',
                    title: "¡Cerrar Conciliación!",
                    text: 'No se puede cerrar la conciliación ya que no tiene viajes conciliados',
                    closeOnConfirm: true,
                    showCancelButton: false,
                    confirmButtonText: "OK"
                });

            } else {
                swal({
                        title: "¡Cerrar Conciliación!",
                        text: "¿Desea cerrar la conciliación?",
                        type: "info",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        confirmButtonText: "Si, Cerrar",
                        cancelButtonText: "No",
                        showLoaderOnConfirm: true
                    },
                    function () {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'PATCH',
                                action: 'cerrar'
                            },
                            success: function (response) {
                                if (response.status_code = 200) {
                                    swal({
                                            type: 'success',
                                            title: '¡Hecho!',
                                            text: 'Conciliación cerrada correctamente',
                                            showCancelButton: false,
                                            confirmButtonText: 'OK',
                                            closeOnConfirm: true
                                        },
                                        function () {
                                            _this.fetchConciliacion();
                                        });
                                }
                            },
                            error: function (error) {
                                swal({
                                    type: 'error',
                                    title: '¡Error!',
                                    text: App.errorsToString(error.responseText)
                                });
                                _this.fetchConciliacion();
                            }
                        });
                    });
            }
        },

        aprobar: function(e) {
            e.preventDefault();
            var _this = this;
            var url = App.host + '/conciliaciones/' + _this.conciliacion.id;
            swal({
                title: "¡Aprobar Conciliación!",
                text: "¿Desea aprobar la conciliación?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Si, Aprobar",
                cancelButtonText: "No",
                showLoaderOnConfirm: true
            },
            function(){
                $.ajax({
                    url: url,
                    type : 'POST',
                    data : {
                        _method : 'PATCH',
                        action : 'aprobar'
                    },
                    success: function(response) {
                        if(response.status_code = 200) {
                            swal({
                                type: 'success',
                                title: '¡Hecho!',
                                text: 'Conciliación aprobada correctamente',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                closeOnConfirm: true
                            },
                            function () {
                                _this.fetchConciliacion();
                            });
                        }
                    },
                    error: function (error) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                        _this.fetchConciliacion();
                    }
                });
            });
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
                success: function (response)
                {
                    _this.guardando = false;
                    $('#resultados').modal('hide');
                    _this.resultados = [];

                    swal({
                        type: 'success',
                        title: '¡Viajes Conciliados Correctamente!',
                        text: response.registros + ' Viajes conciliados',
                        showConfirmButton: true
                    });

                    _this.fetchConciliacion();
                },
                error: function (error) {
                    swal({
                        type: 'error',
                        title: '¡Error!',
                        text: App.errorsToString(error.responseText)
                    });
                    _this.fetchConciliacion();
                }
            });
        },

        agregar: function(e) {
            e.preventDefault();

            this.form.errors = [];
            var _this = this;
            var url = $('.form_buscar').attr('action');
            var data = $('.form_buscar').serialize();
            this.guardando = true;


            $.ajax({
                url  : url,
                data : data,
                type : 'POST',
                success: function (response) {
                    if(response.detalles != null) {
                        _this.conciliacion.detalles.push(response.detalles);
                        _this.guardando = false;
                        swal({
                            type: 'success',
                            title: '¡Viaje Conciliado Correctamente!',
                            text: response.registros + ' Viajes conciliados',
                            showConfirmButton: false,
                            timer: 500
                        });
                        _this.conciliacion.importe = response.importe;
                        _this.conciliacion.volumen = response.volumen;
                        _this.conciliacion.num_viajes += 1;
                        _this.conciliacion.rango = response.rango;
                        _this.conciliacion.importe_viajes_manuales = response.importe_viajes_manuales;
                        _this.conciliacion.volumen_viajes_manuales = response.volumen_viajes_manuales;
                        _this.conciliacion.porcentaje_importe_viajes_manuales = response.porcentaje_importe_viajes_manuales;
                        _this.conciliacion.porcentaje_volumen_viajes_manuales = response.porcentaje_volumen_viajes_manuales;
                        _this.conciliacion.volumen_viajes_moviles = response.volumen_viajes_moviles;
                        _this.conciliacion.importe_viajes_moviles = response.importe_viajes_moviles;

                        $('.ticket').val('');
                        $('.ticket').focus();

                    } else {
                        _this.guardando = false;
                        swal({
                            type: 'warning',
                            title: '¡Error!',
                            text: response.msg,
                            showConfirmButton: true,
                            timer: 1500
                        });

                        $('.ticket').val('');
                        $('.ticket').focus();
                    }
                },
                error: function (error) {
                    _this.guardando = false
                    $('.ticket').val('');
                    $('.ticket').focus();
                    
                    swal({
                        type: 'error',
                        title: '¡Error!',
                        text: App.errorsToString(error.responseText)
                    });
                }
            })
        },

        buscar: function(e) {
            e.preventDefault();

            var _this = this;
            this.form.errors = [];
            this.guardando = true;

            var data = $('.form_buscar').serialize();
            this.$http.get(App.host + '/viajes?tipo=conciliar&' + data).then((response) => {
                _this.resultados = response.body.data;
                if(_this.resultados.length) {
                    _this.guardando = false;
                    $('#resultados').modal('show');
                } else {
                    _this.guardando = false;
                    swal({
                        type: 'warning',
                        title: '¡Sin Resultados!',
                        text: 'Ningún viaje coincide con los datos de consulta',
                        showConfirmButton: true
                    });
                }
            }, (error) => {
                _this.guardando = false;
                App.setErrorsOnForm(this.form, error.body);

            });
        },

        cambiar_cubicacion: function (detalle) {

            var _this = this;
            swal({
                title: "¡Cambiar Cubicación!",
                text: "Cubicación Actual : " + detalle.cubicacion_camion,
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Nueva Cubicación.",
                confirmButtonText: "Si, Cambiar",
                cancelButtonText: "No",
                showLoaderOnConfirm: true
            },
            function(inputValue){
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("¡Escriba la nueva Cubicación!");
                    return false
                } if (! $.isNumeric(inputValue)) {
                    swal.showInputError("¡Por favor introduzca sólo números!");
                    return false;
                }
                $.ajax({
                    url: App.host + '/viajes/' + detalle.id ,
                    type : 'POST',
                    data : {
                        _method : 'PATCH',
                        cubicacion : inputValue,
                        tipo : 'cubicacion',
                        id_conciliacion: _this.conciliacion.id
                    },
                    success: function(response) {
                        if(response.status_code = 200) {
                            _this.fetchConciliacion();
                            swal({
                                type: 'success',
                                title: '¡Hecho!',
                                text: 'Cubicacion cambiada correctamente',
                                showCancelButton: false,
                                confirmButtonText: '' +
                                'OK',
                                closeOnConfirm: true
                            });
                        }
                    },
                    error: function (error) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                        _this.fetchConciliacion();
                    }
                });
            });
        },

        eliminar_detalle: function (idconciliacion_detalle) {
            var _this = this;
            var url = App.host + '/conciliacion/' + this.conciliacion.id + '/detalles/' + idconciliacion_detalle;
            swal({
                title: "¡Cancelar viaje de la Conciliación!",
                text: "¿Esta seguro de que deseas quitar el viaje de la conciliación?",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Motivo de la cancelación.",
                confirmButtonText: "Si, Quitar",
                cancelButtonText: "No",
                showLoaderOnConfirm: true

            },
            function(inputValue){
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("Escriba el motivo de la cancelación!");
                    return false
                }
                _this.guardando = true;
                _this.$http.post(url, {_method : 'DELETE', motivo : inputValue}).then((response) => {
                    if(response.body.status_code == 200) {
                        _this.guardando = false;
                        _this.fetchConciliacion();
                        swal({
                            type: 'success',
                            title: '¡Hecho!',
                            text: 'Viaje cancelado correctamente',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            closeOnConfirm: true
                        });
                    }
                }, (error) => {
                    _this.guardando = false;
                    swal({
                        type: 'error',
                        title: '¡Error!',
                        text: App.errorsToString(error.body)
                    });
                    _this.fetchConciliacion();
                });
            });
        },

        reabrir: function (e) {
            e.preventDefault();

            var _this = this;
            var url = $(e.target).attr('href');

            swal({
                title: "Re-abrir conciliación?",
                text: "¿Esta seguro de que desea Re-abrir la conciliación?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                confirmButtonColor: "#ec6c62"
            }, function() {
                console.log('reabrir');
                console.log(url);
            });
        }
    }
});
