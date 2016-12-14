@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.origenes_usuarios')) }}</h1>
{!! Breadcrumbs::render('origenes_usuarios.index') !!}
<hr>
<section id="origenes_usuarios">
    <div class="col-md-4">
        {!! Form::label('Usuarios', 'Seleccione un Usuario')!!}
        {!! Form::select('Usuarios', $usuarios, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario...', 'v-model' => 'usuario', 'v-on:change' => 'fetchOrigenes']) !!}
    </div>
    <div class="table-responsive col-md-8 col-md-offset-2">
        <table v-show="origenes" class="table table-hover" id="origenes_usuarios_table">
            <thead>
                <tr>
                    <th></th>
                    <th>Origen</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="origen in origenes">
                    <td>
                        <img v-bind:style="{cursor: origen.cursor}" v-on:click="asignar(origen)" v-bind:src="origen.img" v-bind:title="origen.title"/>
                    </td>
                    <td>@{{ origen.descripcion }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
</section>
@stop
@section('scripts')
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.4/vue.js"></script>
<script src="https://cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-async-data/1.0.2/vue-async-data.js"></script>
<script>
    new Vue({
        el: '#origenes_usuarios',
        data: {
            usuario: '',
            origenes: []
        },
        methods: {
            fetchOrigenes: function() {
                var self = this;
                if(self.usuario) {
                    self.$http.get(App.host + '/usuarios/' + self.usuario + '/origenes').then((response) => {
                        self.origenes = response.body;
                    }, (response) => {}); 
                } else {
                   self.origenes = [];
                }
            },
            asignar: function(origen) {
                var self = this;
                if(origen.estatus == 1 || origen.estatus == 2) {
                    swal({   
                        title: "¿Desea continuar?",   
                        text: "¡La asignación del origen cambiara!",   
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Aceptar",   
                        cancelButtonText: "Cancelar",   
                        closeOnConfirm: false }, 
                    function(){   
                        self.$http.post(App.host + '/usuarios/' + self.usuario + '/origenes/' + origen.id, {_token: App.csrfToken}).then((response) => {
                            self.fetchOrigenes();
                            swal({   
                                title: "",  
                                text: "La asignación del origen ha cambiado",   
                                timer: 750,   
                                showConfirmButton: false ,
                                type: "success"
                            });
                        }, (response) => {});   
                    });
                }
            }
        }
    });
</script>
@stop