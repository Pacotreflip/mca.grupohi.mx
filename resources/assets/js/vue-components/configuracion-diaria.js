/**
 * Created by JFEsquivel on 27/04/2017.
 */

Vue.component('configuracion-diaria', {
    props: ['rol_checador'],
    data: function () {
        return {
            checadores : [],
            tiros      : [],
            origenes   : [],
            esquemas   : [],
            perfiles   : [],
            telefonos  : [],
            form: {
                errors : []
            },
            guardando  : false,
            cargando   : false
        }
    },

    created: function () {
        this.initialize();
    },

    computed: {
        con_esquema: function () {
            return this.tiros.filter(function(tiro) {
                if(tiro.esquema.id != '') {
                    return true;
                }
                return false;
            });
        },
        para_origen: function () {
            return this.perfiles.filter(function(perfil) {
                if(perfil.id_esquema == '1') {
                    return true;
                }
                return false;
            });
        },
        para_tiro: function () {
            return this.perfiles.filter(function(perfil) {
                if(perfil.id_esquema == '2') {
                    return true;
                }
                return false;
            });
        }
    },

    methods: {
        initialize: function () {
            var _this = this;
            var url = App.host + '/configuracion-diaria';

            $.ajax({
                type       : 'GET',
                url        : url,
                data       : {
                    type : 'init'
                },
                beforeSend : function () {
                    _this.cargando = true;
                },
                success    : function (response) {
                    _this.checadores = response.checadores;
                    _this.checadores.forEach(function (checador) {
                        if(! checador.configuracion) {
                            Vue.set(checador, 'configuracion', {
                                tipo : '',
                                ubicacion : {
                                    id : '',
                                    descripcion : ''
                                },
                                id_perfil : '',
                                turno : ''
                            });
                        }
                        Vue.set(checador, 'guardando', false);
                    });
                    _this.tiros = response.tiros;
                    _this.tiros.forEach(function (tiro) {
                        if(! tiro.esquema) {
                            Vue.set(tiro, 'esquema' , {
                                'id' : '',
                                'name' : ''
                            });
                        }
                        Vue.set(tiro, 'guardando' , false);
                    });

                    _this.origenes = response.origenes;
                    _this.perfiles = response.perfiles;
                    _this.esquemas = response.esquemas;
                    _this.telefonos = response.telefonos;
                },
                error      : function (error) {
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
                complete   : function () {
                    _this.cargando = false;
                }
            });
        },

        cambiar_esquema: function (tiro, e) {
            e.preventDefault();
            var _this = this;
            var url = App.host + '/tiros/' + tiro.id;
            var data = {
                'id_tiro' : tiro.id,
                'id_esquema' : tiro.esquema.id,
                '_method' : 'PATCH',
                'action' : 'cambiar_esquema'
            };

            $.ajax({
                type : 'POST',
                url  : url,
                data : data,
                beforeSend: function () {
                    tiro.guardando = true;
                },
                success: function (response) {
                    if(response.status_code == 200) {
                        var nuevo_tiro = response.tiro;
                        nuevo_tiro.guardando = false;
                        Vue.set(_this.tiros, _this.tiros.indexOf(tiro) , nuevo_tiro);
                        swal({
                            type : 'success',
                            title : '¡Configuración Correcta!',
                            text : 'El esquema del tiro <strong>' + tiro.descripcion + '</strong><br> ha sido cambiado a <strong>' + nuevo_tiro.esquema.name + '</strong>',
                            html : true
                        });
                    } else if(response.status_code == 304){
                        swal({
                            type : 'warning',
                            title : '¡Alerta!',
                            text : 'El tiro <strong>' + tiro.descripcion + '</strong><br> se esta utilizando en ' + response.num + ' configuarciones<br><strong>¿Realmente desea cambiar el esquema del tiro? </strong><br><small>¡Se borrarán dichas configuraciones!</small>',
                            html : true,
                            showCancelButton: true,
                            confirmButtonText: "Si, cambiar",
                            cancelButtonText: "No, cancelar",
                        }, () => _this.force_cambiar_esquema(tiro));
                    }
                },
                error: function (error) {
                    if (error.status == 422) {
                        swal({
                            type : 'error',
                            title : '¡Error!',
                            text : App.errorsToString(error.responseJSON)
                        });
                    } else if (error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                },
                complete: function () {
                    tiro.guardando = false;
                }
            });
        },

        force_cambiar_esquema: function (tiro) {
            var _this = this;
            var url = App.host + '/tiros/' + tiro.id;
            var data = {
                'id_tiro' : tiro.id,
                'id_esquema' : tiro.esquema.id,
                '_method' : 'PATCH',
                'action' : 'force_cambiar_esquema'
            };

            $.ajax({
                type : 'POST',
                url  : url,
                data : data,
                beforeSend: function () {
                    tiro.guardando = true;
                },
                success: function (response) {
                    _this.initialize();
                    swal({
                        type : 'success',
                        title : '¡Configuración Correcta!',
                        text : 'El esquema del tiro <strong>' + tiro.descripcion + '</strong><br> ha sido cambiado a <strong>' + response.tiro.esquema.name + '</strong>',
                        html : true
                    });
                },
                error: function (error) {
                    if (error.status == 422) {
                        swal({
                            type : 'error',
                            title : '¡Error!',
                            text : App.errorsToString(error.responseJSON)
                        });
                    } else if (error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                },
                complete: function () {
                    tiro.guardando = false;
                }
            });
        },

        tiro_by_id: function (id) {
            var result = {};
            this.tiros.forEach(function (tiro) {
                if (tiro.id == id) {
                    result = tiro;
                }
            });
            return result;
        },

        origen_by_id: function (id) {
            var result = {};
            this.origenes.forEach(function (origen) {
                if(origen.id == id) {
                    result = origen;
                }
            });
            return result;
        },

        set_ubicacion: function (user, e) {
            var id = $(e.currentTarget).val();
            if (user.configuracion.tipo == 1) {
                Vue.set(user.configuracion, 'ubicacion', this.tiro_by_id(id));
            } else if (user.configuracion.tipo == 0) {
                Vue.set(user.configuracion, 'ubicacion', this.origen_by_id(id));
            }
            Vue.set(user.configuracion, 'id_perfil', '');
            Vue.set(user.configuracion, 'turno', '');
        },

        clear_ubicacion: function (user) {
            Vue.set(user.configuracion, 'ubicacion', {
                id: '',
                descripcion: ''
            });
        },

        guardar_configuracion: function (user) {
            var data = {
                'id_usuario' : user.id,
                'tipo' : user.configuracion.tipo,
                'id_ubicacion' : user.configuracion.ubicacion.id,
                'id_perfil' : user.configuracion.id_perfil,
                'turno' : user.configuracion.turno,
                'id_telefono' : $('#'+user.id).val()
            };

            var _this = this;
            var url = App.host + '/configuracion-diaria';

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                beforeSend: function () {
                    user.guardando = true;
                },
                success: function (response) {
                    var checador = response.checador;
                    checador.guardando = false;
                    Vue.set(_this.checadores, _this.checadores.indexOf(user), checador);
                    _this.telefonos = response.telefonos;
                    swal({
                        type : 'success',
                        title : '¡Configuración Correcta!',
                        text: 'Configuración establecida correctamente<br> para el usuario </strong>' + user.nombre +'</strong>',
                        html: true
                    });
                },
                error: function (error) {
                    if (error.status == 422) {
                        swal({
                            type : 'error',
                            title : '¡Error!',
                            text : App.errorsToString(error.responseJSON)
                        });
                    } else if (error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                },
                complete: function () {
                    user.guardando = false;
                }
            });
        },

        quitar_configuracion: function (user) {
            var _this = this;
            swal({
                type : 'warning',
                title : '¡Alerta!',
                text : '¿Realmente desea eliminar la configuración para el checador<br>'+user.nombre+'?',
                html : true,
                showCancelButton: true,
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar",
            }, () => _this.eliminar(user));
        },

        eliminar:function (user) {
            var url = App.host + '/configuracion-diaria/' + user.configuracion.id;
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _method: 'DELETE'
                },
                beforeSend: function () {
                    user.guardando = true;
                },
                success: function () {
                    swal({
                        type: 'success',
                        text: 'Configuración eliminada correctamente',
                        title: 'Información'
                    });
                    Vue.set(user, 'configuracion',{
                        tipo : '',
                        ubicacion : {
                            id : '',
                            descripcion : ''
                        },
                        id_perfil : '',
                        turno: ''
                    });
                },
                error: function (error) {
                    if (error.status == 422) {
                        swal({
                            type : 'error',
                            title : '¡Error!',
                            text : App.errorsToString(error.responseJSON)
                        });
                    } else if (error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                },
                complete: function () {
                    user.guardando = false;
                }
            });
        },

        confirmar_quitar_checador: function (user) {
            var _this = this;
            swal({
                type : 'warning',
                title : '¡Alerta!',
                text : "¿Realmente desea quitar el permiso de 'Checador' para el usuario<br><strong>"+user.nombre+"</strong>?",
                html : true,
                showCancelButton: true,
                confirmButtonText: "Si, quitar",
                cancelButtonText: "No, cancelar",
            }, () => _this.quitar_checador(user));
        },

        quitar_checador: function (user) {
            var _this = this;
            var url = App.host + '/user/' + user.id + '/roles/' + _this.rol_checador;
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _method: 'DELETE'
                },
                beforeSend: function () {
                    user.guardando = true;
                },
                success: function (response) {
                    Vue.delete(_this.checadores, _this.checadores.indexOf(user));
                    _this.telefonos = response.telefonos;
                },
                error: function (error) {
                    if (error.status == 422) {
                        swal({
                            type : 'error',
                            title : '¡Error!',
                            text : App.errorsToString(error.responseJSON)
                        });
                    } else if (error.status == 500) {
                        swal({
                            type: 'error',
                            title: '¡Error!',
                            text: App.errorsToString(error.responseText)
                        });
                    }
                }
            });
        }
    }
});