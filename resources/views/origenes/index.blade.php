@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.origins')) }}
  <a href="{{ route('origenes.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_origin') }}</a>
  <a href="{{ route('csv.origenes') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Excel</a>
</h1>
{!! Breadcrumbs::render('origenes.index') !!}
<hr>
<div class="table-responsive">
  <table class="table table-striped small"  id="index_origenes" >
    <thead>
      <tr>
        <th>Clave</th>
        <th>Tipo</th>
        <th>Descripción</th>
        <th>Fecha y hora registro</th>
        <th>Registró</th>
        <th>Estatus</th>
        <th width="160px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($origenes as $origen)
        <tr>
          <td>
            {{ $origen->present()->claveOrigen }}
          </td>
          <td>{{ $origen->tipoOrigen->Descripcion }}</td>
          <td>{{ $origen->Descripcion }}</td>
          <td>{{$origen->created_at->format('d-M-Y h:i:s a')}}</td>
          <td>{{$origen->user_registro->present()->nombreCompleto()}}</td>
          <td>{{ $origen->present()->estatus }}</td>
          <td>

            <a href="{{ route('origenes.show', $origen) }}" title="Ver" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>

            @if($origen->Estatus == 1)
              <button type="submit" title="Desactivar" class="btn btn-xs btn-danger" onclick="desactivar_origen({{$origen->IdOrigen}})"><i class="fa fa-remove"></i></button>
            @else
              <button type="submit" title="Activar" class="btn btn-xs btn-success" onclick="activar_origen({{$origen->IdOrigen}})"><i class="fa fa-check"></i></button>
            @endif

          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <form id='delete' method="post">
    <input type='hidden' name='motivo' value/>
    {{csrf_field()}}
    <input type="hidden" name="_method" value="delete"/>
  </form>
 </div>
@stop

@section('scripts')
  <script>

      var auth_config = {
          auto_filter: true,
          col_0: 'input',
          col_1: 'select',
          col_2: 'input',
          col_3: 'input',
          col_4: 'select',
          col_5: 'select',
          col_6: 'none',
          base_path: App.tablefilterBasePath,
          auto_filter: true,
          paging: false,
          rows_counter: true,
          rows_counter_text: 'Origenes: ',
          btn_reset: true,
          btn_reset_text: 'Limpiar',
          clear_filter_text: 'Limpiar',
          loader: true,
          page_text: 'Pagina',
          of_text: 'de',
          help_instructions: false,
          extensions: [{ name: 'sort' }]
      };
      var tf = new TableFilter('index_origenes', auth_config);
      tf.init();

      function desactivar_origen(id) {
          var form = $('#delete');
          var url=App.host +"/origenes/"+id;

          swal({
                  title: "¡Desactivar origen!",
                  text: "¿Esta seguro de que deseas desactivar el origen?",
                  type: "input",
                  showCancelButton: true,
                  closeOnConfirm: false,
                  inputPlaceholder: "Motivo de la desactivación.",
                  confirmButtonText: "Si, Desactivar",
                  cancelButtonText: "No, Cancelar",
                  showLoaderOnConfirm: true

              },
              function(inputValue){
                  if (inputValue === false) return false;
                  if (inputValue === "") {
                      swal.showInputError("Escriba el motivo de la eliminación!");
                      return false
                  }
                  form.attr("action", url);
                  $("input[name=motivo]").val(inputValue);
                  form.submit();
              });
      }

      function activar_origen(id) {

          var form = $('#delete');
          var url=App.host +"/origenes/"+id;

          swal({
                  title: "¡Activar Origen!",
                  text: "¿Esta seguro de que deseas activar el origen?",
                  type: "warning",
                  showCancelButton: true,
                  closeOnConfirm: false,
                  inputPlaceholder: "Motivo de la activación.",
                  confirmButtonText: "Si, Activar",
                  cancelButtonText: "No, Cancelar",
                  showLoaderOnConfirm: true

              },
              function(){
                  form.attr("action", url);
                  $("input[name=motivo]").val("");
                  form.submit();
              });
      }


  </script>
@endsection

