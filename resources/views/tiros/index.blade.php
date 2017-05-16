@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.tiros')) }}
  <a href="{{ route('tiros.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> NUEVO TIRO</a>
    <a href="{{ route('csv.tiros') }}" style="margin-right: 5px" class="btn btn-default pull-right"><i class="fa fa-file-excel-o"></i> EXCEL</a>
</h1>
{!! Breadcrumbs::render('tiros.index') !!}
<hr>
<div class="table-responsive">
  <table class="table table-striped small" id="index_tiros">
    <thead>
      <tr>
        <th>Clave</th>
        <th>Descripción</th>
        <th>Fecha y hora registro</th>
        <th>Registró</th>
        <th>Estatus</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($tiros as $tiro)
        <tr>
          <td>
            {{ $tiro->present()->claveTiro }}
          </td>
          <td>{{ $tiro->Descripcion }}</td>
          <td>{{$tiro->created_at->format('d-M-Y h:i:s a')}}</td>
          <td>{{$tiro->user_registro->present()->nombreCompleto()}}</td>
          <td>{{ $tiro->present()->estatus }}</td>
          <td>
            <a href="{{ route('tiros.show', $tiro) }}" title="Ver" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
          @if($tiro->Estatus == 1)
              <button type="submit" title="Desactivar" class="btn btn-xs btn-danger" onclick="desactivar_tiro({{$tiro->IdTiro}})"><i class="fa fa-remove"></i></button>
            @else
              <button type="submit" title="Activar" class="btn btn-xs btn-success" onclick="activar_tiro({{$tiro->IdTiro}})"><i class="fa fa-check"></i></button>
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
          col_1: 'input',
          col_2: 'input',
          col_3: 'select',
          col_4: 'select',
          col_5: 'none',
          base_path: App.tablefilterBasePath,
          auto_filter: true,
          paging: false,
          rows_counter: true,
          rows_counter_text: 'Tiros: ',
          btn_reset: true,
          btn_reset_text: 'Limpiar',
          clear_filter_text: 'Limpiar',
          loader: true,
          page_text: 'Pagina',
          of_text: 'de',
          help_instructions: false,
          extensions: [{ name: 'sort' }]
      };
      var tf = new TableFilter('index_tiros', auth_config);
      tf.init();

      function desactivar_tiro(id) {
          var form = $('#delete');
          var url=App.host +"/tiros/"+id;

          swal({
                  title: "¡Desactivar tiro!",
                  text: "¿Esta seguro de que deseas desactivar el tiro?",
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

      function activar_tiro(id) {

          var form = $('#delete');
          var url=App.host +"/tiros/"+id;

          swal({
                  title: "¡Activar Tiro!",
                  text: "¿Esta seguro de que deseas activar el tiro?",
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