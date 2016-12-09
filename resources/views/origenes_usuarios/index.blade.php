@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.origenes_usuarios')) }}</h1>
{!! Breadcrumbs::render('origenes_usuarios.index') !!}
<hr>
<section id="origenes_usuarios">
    <div class="col-md-4">
        {!! Form::label('Usuarios', 'Seleccione un Usuario')!!}
        {!! Form::select('Usuarios', $usuarios, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario...', 'v-model' => 'usuario', 'v-on:change' => 'showTable']) !!}
    </div>
    <div class="table-responsive col-md-8 col-md-offset-2">
        <table v-if="show" class="table table-hover" id="origenes_usuarios_table">
            <thead>
                <tr>
                    <th></th>
                    <th>Origen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($origenes as $origen)
                <tr>
                    <td></td>
                    <td>{{ $origen->Descripcion }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <pre>@{{ $data | json }}</pre>
</section>
@stop
@section('scripts')
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.4/vue.js"></script>

  <script>
    new Vue({
      el: '#origenes_usuarios',
      data: {
        usuario: '',
        origenes: [],
        show: false,
      },
      methods: {
          showTable: function() {
              if(self.usuario !== "") {
                  self.show = true;
              }
          }
      }
    });
  </script>
@stop