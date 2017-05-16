@extends('layout')

@section('content')
<h1>{{ strtoupper(trans('strings.rutas')) }}
    <a href="{{ route('rutas.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> {{ trans('strings.new_ruta') }}</a>
    <a style="margin-right: 5px" href="{{ route('csv.rutas') }}" class="btn btn-info pull-right"><i class="fa fa-file-excel-o"></i> Descargar</a>
</h1>
{!! Breadcrumbs::render('rutas.index') !!}
<hr>
<div class="table-responsive">
    <table id="index_rutas" class="table table-hover table-bordered small">
        <thead>
        <tr>
            <th style="text-align: center" colspan="8">Ruta</th>
            <th style="text-align: center" colspan="2">Cronometría Activa</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>Ruta</th>
            <th>Origen</th>
            <th>Tiro</th>
            <th>Tipo</th>
            <th>1er. KM</th>
            <th>KM Subsecuentes</th>
            <th>KM Adicionales</th>
            <th>KM Total</th>
            <th>Tiempo Minimo</th>
            <th>Tolerancia</th>
            <th>Registró</th>
            <th>Fecha/Hora Registro</th>
            <th width="100px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rutas as $ruta)
        <tr>
          <td>{{ $ruta->present()->claveRuta }}</td>
          <td>{{ $ruta->origen->Descripcion }}</td>
          <td>{{ $ruta->tiro->Descripcion }}</td>
          <td>{{ $ruta->tipoRuta->Descripcion }}</td>
          <td>{{ $ruta->PrimerKm . ' km' }}</td>
          <td>{{ $ruta->KmSubsecuentes . ' km' }}</td>
          <td>{{ $ruta->KmAdicionales . ' km' }}</td>
          <td>{{ $ruta->TotalKM . ' km' }}</td>
          <td>{{ $ruta->cronometria->TiempoMinimo }}</td>
          <td>{{ $ruta->cronometria->Tolerancia }}</td>
          <td>{{ $ruta->user_registro }}</td>
          <td>{{ $ruta->created_at->format('d-M-Y h:i:s a') }}</td>
            <td>
                <a href="{{ route('rutas.show', $ruta) }}" title="Ver" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                @if($ruta->Estatus == 1)
                    <button type="submit" title="Desactivar" class="btn btn-xs btn-danger" onclick="desactivar_ruta('{{$ruta->IdRuta}}', '{{$ruta->present()->claveRuta}}')"><i class="fa fa-remove"></i></button>
                @else
                    <button type="submit" title="Activar" class="btn btn-xs btn-success" onclick="activar_ruta('{{$ruta->IdRuta}}', '{{$ruta->present()->claveRuta}}')"><i class="fa fa-check"></i></button>
            @endif
            </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Form desactivar Ruta -->
<form id="desactivar_ruta" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="delete">
    <input type="hidden" name="motivo" value/>
</form>

@stop
@section('scripts')
    <script>
        //Filtro de la tabla de Rutas
        var auth_config = {
            auto_filter: true,
            col_0: 'input',
            col_1: 'select',
            col_2: 'select',
            col_3: 'select',
            col_4: 'none',
            col_5: 'none',
            col_6: 'none',
            col_7: 'none',
            col_8: 'none',
            col_9: 'none',
            col_10: 'select',
            col_11: 'input',
            col_12: 'none',
            base_path: App.tablefilterBasePath,
            auto_filter: true,
            paging: false,
            rows_counter: true,
            rows_counter_text: 'Rutas: ',
            btn_reset: true,
            btn_reset_text: 'Limpiar',
            clear_filter_text: 'Limpiar',
            loader: true,
            page_text: 'Pagina',
            of_text: 'de',
            help_instructions: false,
            extensions: [{ name: 'sort' }]
        };
        var tf = new TableFilter('index_rutas', auth_config);
        tf.init();

        //Desactivar Ruta
        function desactivar_ruta(id, ruta) {
            var url = App.host + '/rutas/' + id;
            var form = $('#desactivar_ruta');
            swal({
                    title: "¡Desactivar Ruta!",
                    text: "¿Esta seguro de que deseas desactivar la ruta <br><strong>" + ruta + "</strong>?",
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
        function activar_ruta(id, ruta) {
            var url = App.host + '/rutas/' + id;
            var form = $('#desactivar_ruta');
            swal({
                    title: "¡Activar Ruta!",
                    text: "¿Esta seguro de que deseas activar la ruta <br><strong>" + ruta + "</strong>?",
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