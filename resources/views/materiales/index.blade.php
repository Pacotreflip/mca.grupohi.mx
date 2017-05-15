@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.materials')) }}
  <a href="{{ route('materiales.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_material') }}</a>
    <a href="{{ route('csv.materiales') }}" style="margin-right: 5px" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('materiales.index') !!}
<hr>
<div class="table-responsive">
  <table id="index_materiales" class="table table-striped small">
    <thead>
      <tr>
        <th>ID Material</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($materiales as $material)
        <tr>
          <td>{{ $material->IdMaterial }}</td>
          <td>{{ $material->Descripcion }}</td>
          <td>{{ $material->present()->estatus }}</td>
          <td>
            <a href="{{ route('materiales.show', $material) }}" title="Ver" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
            @if($material->Estatus == 1)
              <button type="submit" title="Desactivar" class="btn btn-xs btn-danger" onclick="desactivar_material('{{$material->IdMaterial}}', '{{$material->Descripcion}}')"><i class="fa fa-ban"></i></button>
            @else
              <button type="submit" title="Activar" class="btn btn-xs btn-success" onclick="activar_material('{{$material->IdMaterial}}', '{{$material->Descripcion}}')"><i class="fa fa-plus"></i></button>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Form desactivar Material -->
<form id="desactivar_material" method="POST">
  {{ csrf_field() }}
  <input type="hidden" name="_method" value="delete">
  <input type="hidden" name="motivo" value/>
</form>
@stop
@section('scripts')
  <script>
    //Desactivar Material
    var auth_config = {
        auto_filter: true,
        col_0: 'input',
        col_1: 'input',
        col_2: 'select',
        col_3: 'none',
        base_path: App.tablefilterBasePath,
        col_types: [
            'number',
            'string',
            'boolean'
        ],
        auto_filter: true,
        paging: false,
        rows_counter: true,
        rows_counter_text: 'Materiales: ',
        btn_reset: true,
        btn_reset_text: 'Limpiar',
        clear_filter_text: 'Limpiar',
        loader: true,
        page_text: 'Pagina',
        of_text: 'de',
        help_instructions: false,
        extensions: [{ name: 'sort' }]
    };
    var tf = new TableFilter('index_materiales', auth_config);
    tf.init();

    function desactivar_material(id, material) {
      var url = App.host + '/materiales/' + id;
      var form = $('#desactivar_material');
      swal({
        title: "¡Desactivar Material!",
        text: "¿Esta seguro de que deseas desactivar el material <br><strong>" + material + "</strong>?",
        type: "input",
        html: true,
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
          swal.showInputError("Escriba el motivo de la desactivación!");
          return false
        }
        form.attr("action", url);
        $("input[name=motivo]").val(inputValue);
        form.submit();
      });
    }

    //Reactivar Material
    function activar_material(id, material) {
      var url = App.host + '/materiales/' + id;
      var form = $('#desactivar_material');
      swal({
        title: "¡Activar Material!",
        text: "¿Esta seguro de que deseas activar el material <br><strong>" + material + "</strong>?",
          html:true,
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false,
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
