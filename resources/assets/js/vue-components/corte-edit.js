Vue.component('corte-edit', {
    data: function() {
        return {
            'corte': {
                'id': '',
                'viajes_netos': []
            },
            'form': {
                'busqueda': '',
                'errors': [],
                'data': {
                    'id_material': '',
                    'id_origen': '',
                    'id_tiro': '',
                    'justificacion': '',
                    'cubicacion': '',
                    'id': ''
                },
            },
            'guardando': false,
            'cargando': false,
            'viaje' : {},
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
                if(viaje_neto.corte_cambio) {
                    return true;
                }
                return false;
            });
        },

        resultados: function () {
            var _this = this;
            return this.corte.viajes_netos.filter(function (viaje_neto) {
                if(viaje_neto.codigo == _this.form.busqueda.replace(/ /g,'')) {
                    return true;
                } else {
                    return false;
                }
            });
        }
    },

    methods: {
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

        editar: function (e) {
            e.preventDefault();
            var viaje = this.viaje;
            if(viaje.corte_cambio) {
                this.form.data.id_origen = viaje.corte_cambio.origen_nuevo ? viaje.corte_cambio.origen_nuevo.IdOrigen : viaje.id_origen;
                this.form.data.id_tiro = viaje.corte_cambio.tiro_nuevo ? viaje.corte_cambio.tiro_nuevo.IdTiro : viaje.id_tiro;
                this.form.data.id_material = viaje.corte_cambio.material_nuevo ? viaje.corte_cambio.material_nuevo.IdMaterial : viaje.id_material;
                this.form.data.cubicacion = viaje.corte_cambio.cubicacion_nueva ? viaje.corte_cambio.cubicacion_nueva : viaje.cubicacion;
                this.form.data.justificacion = viaje.corte_cambio.justificacion;
            } else {
                this.form.data.id_origen = viaje.id_origen;
                this.form.data.id_tiro = viaje.id_tiro;
                this.form.data.id_material = viaje.id_material;
                this.form.data.cubicacion = viaje.cubicacion;
                this.form.data.justificacion = '';
            }
            this.form.data.id = viaje.id;
            this.form.errors = [];
            $('#edit_modal').modal('show');
        },

        informacion: function (viaje) {
            this.viaje = viaje;
            this.index = this.corte.viajes_netos.indexOf(viaje);
            $('#info_modal').modal('show');
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
                    _this.viaje = response.viaje_neto;
                    $('#edit_modal').modal('hide');
                    $('#info_modal').modal('hide');
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
            });
        },

        formato: function (val) {
            return numeral(val).format('0,0.00');
        },

        confirmar_descartar: function () {
            e.preventDefault();
            swal({
                title: "Revertir Modificaciones",
                text: "¿Esta seguro de desea revertir los cambios hechos en el viaje?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Revertir",
                cancelButtonText: "No, Cancelar",
                confirmButtonColor: "#ec6c62"
            }, () => this.descartar());
        },

        descartar: function (e) {
            e.preventDefault();
            var _this = this;
            var url = App.host + '/corte/' + _this.corte.id + '/viajes_netos/' + this.viaje.id + '?action=revertir_modificaciones';
            
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
                    Vue.set(_this.corte.viajes_netos, _this.index, response.viaje_neto);
                    _this.viaje = response.viaje_neto;
                    swal({
                        'type' : 'success',
                        'title' : 'INFORMACIÓN',
                        'text' : 'Cambios revertidos correctamente'
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
            });
        },

        confirmar_confirmacion: function (e) {
            e.preventDefault();
            swal({
                title: "Confirmar el viaje",
                text: "¿Esta seguro de que la información es correcta?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Confirmar",
                cancelButtonText: "No, Cancelar",
                confirmButtonColor: "#ec6c62"
            }, () => this.confirmar_viaje());
        },

        confirmar_viaje: function () {
            var _this = this;
            var url = App.host + '/corte/' + _this.corte.id + '/viajes_netos/' + _this.viaje.id;
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _method: 'PATCH',
                    action: 'confirmar'
                },
                beforeSend: function () {
                    _this.guardando = true;
                },
                success: function (response) {
                    $('#info_modal').modal('hide');
                    Vue.set(_this.corte.viajes_netos, _this.index, response.viaje_neto);
                    swal({
                        'type' : 'success',
                        'title' : 'INFORMACIÓN',
                        'text' : 'Viaje Confirmado Correctamente'
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

        },

        buscar: function (e) {
            e.preventDefault();
            this.form.errors = [];
            if(this.resultados.length) {
                this.informacion(this.resultados[0]);
            } else {
                this.form.errors.push('Ningún viaje coincide con la búsqueda');
            }
        }
    }
});
