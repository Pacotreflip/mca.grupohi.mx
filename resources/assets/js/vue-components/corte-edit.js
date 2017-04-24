Vue.component('corte-edit', {
    data: function() {
        return {
            'corte': {
                'id': '',
                'viajes_netos': []
            },
            'form': {
                'errors': [],
                'data': {
                    'id_material': '',
                    'id_origen': '',
                    'observaciones': '',
                    'cubicacion': '',
                    'id': ''
                },
            },
            'guardando': false,
            'cargando': false,
            'viaje': {},
            'index' : ''
        }
    },

    created: function () {
        this.fetch();
    },

    computed: {
        modified: function() {
            var _this = this;
            return _this.corte.viajes_netos.filter(function(viaje_neto) {
                if(viaje_neto.modified) {
                    return true;
                }
                return false;
            });
        }
    },

    methods: {
        doSomethingOnHidden: function () {

        },

        fetch: function () {

            var _this = this;
            var id_corte = $('#id_corte').val();
            this.corte.id = id_corte;
            var estatus = $('#estatus').val();
            var url = App.host + '/corte/' + id_corte + '/viajes_netos';

            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function () {
                    _this.form.errors = [];
                    _this.cargando = true;
                },
                success: function (response) {
                    _this.corte.viajes_netos = response.viajes_netos;
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

        confirmar_cierre: function(e) {
            e.preventDefault();

            swal({
                title: "¡Cerrar Corte!",
                text: "¿Esta seguro de que la información es correcta?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, cerrar",
                cancelButtonText: "No, cancelar",
                confirmButtonColor: "#ec6c62"
            }, () => this.cerrar());
        },

        cerrar: function () {
            var _this = this;
            var url = App.host + '/corte/' + _this.corte.id;
            $.ajax({
                type : 'POST',
                url : url,
                data : {
                    _method : 'PATCH',
                    action : 'cerrar'
                },
                beforeSend: function () {
                    _this.guardando = true;
                },
                success: function (response) {
                    window.location = response.path;
                },
                error: function (error) {
                    _this.cargando = false;
                    if (error.status == 422) {
                        App.setErrorsOnForm(_this.form, error.responseJSON);
                    } else if (error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                }
            });
        },

        editar: function (viaje) {

            $('input[name=observaciones]').val(viaje.observaciones);
            this.viaje = viaje;

            this.form.data.id_material = viaje.id_material_nuevo ? viaje.id_material_nuevo : viaje.id_material;
            this.form.data.id_origen = viaje.id_orige_nuevo ? viaje.id_origen_nuevo : viaje.id_origen;
            this.form.data.cubicacion = viaje.cubicacion_nueva ? viaje.cubicacion_nueva : viaje.cubicacion;
            this.form.data.id = viaje.id;
            this.index = this.corte.viajes_netos.indexOf(viaje);

            this.form.errors = [];
            $('#edit_modal').modal('show');
        },

        confirmar_modificacion: function(e) {
            e.preventDefault();
            swal({
                title: "Modificar Viaje",
                text: "¿Esta seguro de que la información es correcta?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Modificar",
                cancelButtonText: "No, Cancelar",
                confirmButtonColor: "#ec6c62"
            }, () => this.modificar());
        },

        modificar: function () {
            var _this = this;
            var url = App.host + '/corte/' + _this.corte.id + '/viajes_netos/' + _this.form.data.id;
            var data = $('#form_modificar').serialize();

            $.ajax({
                type: 'PATCH',
                url: url,
                data: data,
                beforeSend: function () {
                    _this.guardando = true;
                    _this.form.errors = [];
                },
                success: function (response) {
                    Vue.set(_this.corte.viajes_netos, _this.index, response.viaje_neto);
                    $('#edit_modal').modal('hide');
                    if(response.viaje_neto.modified) {
                        swal({
                            'type' : 'success',
                            'title' : 'INFORMACIÓN',
                            'text' : 'Cambios aplicados correctamente'
                        });
                    }
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
        },

        descartar: function (viaje) {
            var _this = this;
            var url = App.host + '/corte/' + _this.corte.id + '/viajes_netos/' + viaje.id + '?action=revertir_modificaciones';
            
            $.ajax({
                type : 'POST',
                url: url,
                data: {
                    _method : 'PATCH'
                },
                beforeSend: function () {
                    _this.guardando = true;
                },
                success: function (response) {
                    Vue.set(_this.corte.viajes_netos, _this.corte.viajes_netos.indexOf(viaje), response.viaje_neto);
                    swal({
                        'type' : 'success',
                        'title' : 'INFORMACIÓN',
                        'text' : 'Cambios aplicados correctamente'
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
                    _this.guardando = false;
                }
                
            })
        }
    }
});
