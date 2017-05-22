Vue.component('roles-permisos', {
    data: function () {
        return {
            usuarios: [],
            roles: [],
            permisos: [],

            selected_rol_id: '',
            selected_rol: {},
            selected_usuario_id: '',
            selected_usuario: {},
            cargando: false,
            guardando: false,
            form: {
                errors: []
            }
        }
    },

    created: function () {
        this.init();
    },

    computed: {
        permisosDisponibles: function () {
            var _this = this;
            if(this.selected_rol_id) {
                return this.permisos.filter(function (permiso) {
                    var i;

                    for (i = 0; i <_this.selected_rol.perms.length; i++) {
                        if (_this.selected_rol.perms[i].id === permiso.id) {
                            return false;
                        }
                    }
                    return true;
                });

            } else {
                return [];
            }
        },
        rolesDisponibles: function () {
            var _this = this;
            if(this.selected_usuario_id) {
                return this.roles.filter(function (rol) {
                    var i;
                    for (i = 0; i < _this.selected_usuario.roles.length; i++) {
                        if (_this.selected_usuario.roles[i].id === rol.id) {
                            return false;
                        }
                    }
                    return true;
                });

            } else {
                return [];
            }
        }
    },

    directives: {
        
    },

    methods: {
        init: function () {
            var url = App.host + '/administracion/roles_permisos/init';
            var _this = this;
            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function () {
                    _this.cargando = true;
                },
                success: function (response) {
                    _this.usuarios = response.usuarios;
                    _this.roles = response.roles;
                    _this.permisos = response.permisos;
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


        roles_store: function (e) {
            e.preventDefault();

            var _this = this;
            var form = $('#roles_store_form');
            var url = form.attr('action');
            var type = form.attr('method');
            var data = form.serialize();

            $.ajax({
                type: type,
                url: url,
                data: data,
                beforeSend: function () {
                    _this.guardando = true;
                },
                success: function (response) {
                    _this.roles.push(response.rol);
                    $("#roles_store_form")[0].reset();
                    swal("Correcto!", "Se ha creado correctamente tu rol.", "success");
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
        permisos_store: function (e) {
            e.preventDefault();

            var _this = this;
            var form = $('#permisos_store_form');
            var url = form.attr('action');
            var type = form.attr('method');
            var data = form.serialize();

            $.ajax({
                type: type,
                url: url,
                data: data,
                beforeSend: function () {
                    _this.guardando = true;
                },
                success: function (response) {
                    _this.permisos.push(response.permiso);
                    $("#permisos_store_form")[0].reset();
                    swal("Correcto!", "Se ha creado correctamente tu rol.", "success");
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
        select_rol: function () {
            $("#leftRolValues").empty();
            $("#rightRolValues").empty();
            var indice=$("#rol").prop('selectedIndex');


            if(this.selected_rol_id) {
                Vue.set(this, 'selected_rol', this.roles[indice-1]);


                this.permisosDisponibles.forEach(function (permiso) {
                    $("#leftRolValues").append("<option id='"+permiso.id+"' value='"+permiso.id+"'>"+permiso.display_name+"</option>");
                });
                this.selected_rol.perms.forEach(function (permiso) {
                    $("#rightRolValues").append("<option id='"+permiso.id+"' value='"+permiso.id+"'>"+permiso.display_name+"</option>");
                });
            } else {
                Vue.set(this, 'selected_rol', {});
            }
        },
        select_usuario: function () {

            var indice=$("#selUser").prop('selectedIndex');

            $("#leftPermisoValues").empty();
            $("#rightPermisoValues").empty();

            if(this.selected_usuario_id) {
                Vue.set(this, 'selected_usuario', this.usuarios[indice-1]);
                this.rolesDisponibles.forEach(function (roles) {
                    $("#leftPermisoValues").append("<option id='"+roles.id+"' value='"+roles.id+"'>"+roles.display_name+"</option>");
                });
                this.selected_usuario.roles.forEach(function (roles) {
                    $("#rightPermisoValues").append("<option id='"+roles.id+"' value='"+roles.id+"'>"+roles.display_name+"</option>");
                });
            } else {
                Vue.set(this, 'selected_usuario', {});
            }
        },

        stop_propagation: function (e) {
            e.stopPropagation();
            
        },

        list_group_action: function (e) {
            e.stopPropagation();

            var t = $("[type=checkbox]");
            if(t.is(":checked")) {
                t.prop("checked",false);
            }
            else {
                t.prop("checked",true);
            }

            if (t.hasClass("all")) {
                t.trigger('click');
            }
        },
//////////////////////////////////////////////////////////////Add roles a usuario
          remove_permiso_click:function (e) {
              e.preventDefault();
              var selectedItem = $("#rightPermisoValues option:selected");
              $("#leftPermisoValues").append(selectedItem);

              var _this = this;
              var idsSeleccionados = [];
              $("#rightPermisoValues option").each(function (index) {
                  idsSeleccionados.push(this.id);
              });

              var form = $('#rol_usuario_form');
              var url = form.attr('action');
              var type = form.attr('method');
              $.ajax({
                  type: type,
                  url: url,
                  data: {
                      'rol': idsSeleccionados,
                      'usuario':$('#selUser').val()
                  },
                  beforeSend: function () {
                      _this.guardando = true;
                      $( "#btnPermisoRight" ).prop( "disabled", true );
                      $( "#btnPermisoLeft" ).prop( "disabled", true );


                  },
                  success: function (response) {
                      _this.usuarios = response.usuarios;
                      _this.roles = response.roles;
                      _this.permisos = response.permisos;
                      swal("Correcto!", "Se ha creado correctamente la configuracion.", "success");
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
                      $( "#btnPermisoRight" ).prop( "disabled", false );
                      $( "#btnPermisoLeft" ).prop( "disabled", false );


                  }
              });
          }


        ,
        add_permiso_click: function (e) {


            var selectedItem = $("#leftPermisoValues option:selected");
            $("#rightPermisoValues").append(selectedItem);

            e.preventDefault();

            var _this = this;
            var idsSeleccionados = [];
            $("#rightPermisoValues option").each(function (index) {
                idsSeleccionados.push(this.id);
            });

            var form = $('#rol_usuario_form');
            var url = form.attr('action');
            var type = form.attr('method');
            $.ajax({
                type: type,
                url: url,
                data: {
                    'rol': idsSeleccionados,
                    'usuario':$('#selUser').val()
                },
                beforeSend: function () {
                    _this.guardando = true;
                    $( "#btnPermisoRight" ).prop( "disabled", true );
                    $( "#btnPermisoLeft" ).prop( "disabled", true );


                },
                success: function (response) {
                    _this.usuarios = response.usuarios;
                    _this.roles = response.roles;
                    _this.permisos = response.permisos;
                    swal("Correcto!", "Se ha creado correctamente la configuracion.", "success");
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
                    $( "#btnPermisoRight" ).prop( "disabled", false );
                    $( "#btnPermisoLeft" ).prop( "disabled", false );


                }
            });
        },


        //////////////////////////Add permisos roles
        remove_rol_click: function (e) {
            var selectedItem = $("#rightRolValues option:selected");
            $("#leftRolValues").append(selectedItem);


            e.preventDefault();

            var _this = this;
            var idsSeleccionados = [];
            $("#rightRolValues option").each(function (index) {
                idsSeleccionados.push(this.id);
            });

            var form = $('#permiso_rol_form');
            var url = form.attr('action');
            var type = form.attr('method');
            $.ajax({
                type: type,
                url: url,
                data: {
                    'permiso': idsSeleccionados,
                    'rol':$('#rol').val()
                },
                beforeSend: function () {
                    _this.guardando = true;
                    $("#btnRolRight").prop("disabled", true);
                    $("#btnRolLeft").prop("disabled", true);
                },
                success: function (response) {
                    _this.usuarios = response.usuarios;
                    _this.roles = response.roles;
                    _this.permisos = response.permisos;
                    swal("Correcto!", "Se ha creado correctamente la configuracion.", "success");
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
                    $("#btnRolRight").prop("disabled", false);
                    $("#btnRolLeft").prop("disabled", false);
                }
            });
        },
        add_rol_click: function (e) {
            e.preventDefault();
            var selectedItem = $("#leftRolValues option:selected");
            $("#rightRolValues").append(selectedItem);


            var _this = this;
            var idsSeleccionados = [];
            $("#rightRolValues option").each(function (index) {
                idsSeleccionados.push(this.id);
            });

            var form = $('#permiso_rol_form');
            var url = form.attr('action');
            var type = form.attr('method');
            $.ajax({
                type: type,
                url: url,
                data: {
                    'permiso': idsSeleccionados,
                    'rol':$('#rol').val()
                },
                beforeSend: function () {
                    _this.guardando = true;
                    $("#btnRolRight").prop("disabled", true);
                    $("#btnRolLeft").prop("disabled", true);
                },
                success: function (response) {
                    _this.usuarios = response.usuarios;
                    _this.roles = response.roles;
                    _this.permisos = response.permisos;
                    swal("Correcto!", "Se ha creado correctamente la configuracion.", "success");
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
                    $("#btnRolRight").prop("disabled", false);
                    $("#btnRolLeft").prop("disabled", false);
                }
            });
        },
        remove_click: function () {
            $('.all').prop("checked",false);
            var items = $("#list2 input:checked:not('.all')");
            items.each(function(idx,item){
                var choice = $(item);
                choice.prop("checked",false);
                choice.parent().appendTo("#list1");
            });
        },
        all_click:function (e) {
            e.stopPropagation();
            var t = $(e.currentTarget);
            if(t.is(":checked")) {
                t.parents('.list-group').find("[type=checkbox]").prop("checked",true);
            }
            else {
                t.parents('.list-group').find("[type=checkbox]").prop("checked",false);
                t.prop("checked",false);
            }
        }
        ,
        permisos_roles: function (e) {
            e.preventDefault();

            $('#idUsuarioSelect').val($('#idUsuario').val());
            var form = $('#permisos_roles_form');
            var url = form.attr('action');
            var type = form.attr('method');
            var data = form.serialize();

            $.ajax({
                type: type,
                url: url,
                data: data,

                beforeSend: function () {
                    _this.guardando = true;
                },
                success: function (response) {
                    _this.roles.push(response.rol);
                    swal("Correcto!", "Se ha creado correctamente tu rol.", "success");
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


    }
});