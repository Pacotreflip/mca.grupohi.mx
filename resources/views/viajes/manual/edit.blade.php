@extends('layout')

@section('content')
<h1>VIAJES MANUALES</h1>
{!! Breadcrumbs::render('viajes.manual.edit') !!}
<hr>
<div class="table-responsive col-md-10 col-md-offset-1 rcorners">
    {!! Form::open(['id' => 'viaje_neto_update' , 'method' => 'patch', 'route' => ['viajes.manual.update']]) !!}
    <table id="viajes_manual_autorizar" class="table table-hover">
        <thead>
            <tr>
                <th>Fecha Llegada</th>
                <th>Hora Llegada</th>
                <th>Camión</th>
                <th>Tiro</th>
                <th>Origen</th>
                <th>Material</th>
                <th>Observaciones</th>
                <th><i style="color: green" class="fa fa-check"></i></th>
                <th><i style="color: red" class="fa fa-remove"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach($viajes as $viaje)
            <tr>
                <td>{{ $viaje->FechaLlegada }}</td>
                <td>{{ $viaje->HoraLlegada }}
                <td>{{ $viaje->Camion }}</td>
                <td>{{ $viaje->Tiro }}</td>
                <td>{{ $viaje->Origen }}
                <td>{{ $viaje->Material }}
                <td>{{ $viaje->Observaciones }}
                <td><input id="{{$viaje->IdViajeNeto}}" type="checkbox" value="20" name="Estatus[{{$viaje->IdViajeNeto}}]"/></td>
                <td><input id="{{$viaje->IdViajeNeto}}" type="checkbox" value="22" name="Estatus[{{$viaje->IdViajeNeto}}]"/></td>
            </tr>
            @endforeach
        </tbody>
    </table> 
    <div class="form-group col-md-12" style="text-align: center; margin-top: 20px">         
        {!! Form::submit('Continuar', ['class' => 'btn btn-success']) !!}
    </div>
    {!! Form::close() !!}
</div>
@stop
@section('scripts')
<script src="{{ asset('tablefilter/tablefilter.js')}}"></script>
<script>
    var filtersConfig = {
        auto_filter: true,
        watermark: [
            'Fecha Llegada', 
            'Hora Llegada', 
            'Camión', 
            'Tiro', 
            'Origen', 
            'Material', 
            'Observaciones'
        ],
        col_2: 'select',
        col_3: 'select',
        col_4: 'select',
        col_5: 'select',
        col_7: 'none',
        col_8: 'none',
        col_9: 'none',
        base_path: '{{ asset("tablefilter"). '/'}}',      
        col_types: [
            'string',            
            'string',
            'string',
            'string',
            'string',
            'string',
            'string',
            'none',
            'none',
            'none'
        ],
        paging: true,
        paging_length: 50,
        rows_counter: true,
        rows_counter_text: 'Viajes: ',
        btn_reset: true,
        btn_reset_text: 'Limpiar',
        clear_filter_text: 'Limpiar',
        loader: true,
        page_text: 'Pagina',
        of_text: 'de',
        help_instructions: false,
        extensions: [{ name: 'sort' }]       
    };
    
    var tf = new TableFilter('viajes_manual_autorizar', filtersConfig);
    tf.init();
    
    $('#viaje_neto_update').submit(function(e) {
        e.preventDefault();
        //var btn = $(this);
        var form = $(this);
        //if(form.serializeArray().length > 2) {
            swal({   
                title: "¿Estás seguro?",   
                text: "Cambiaras es estado del viaje",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Si",  
                cancelButtonText: 'Cancelar',
                closeOnConfirm: false 
            }, function(){   
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        window.location = response.path;
                    },
                    error: function(error) {
                        sweetAlert("Oops...", "Error Interno del Servidor!", "error");
                    }
                });
            });
        //}
    });
    
    $("input:checkbox").click(function(e){
        if(this.checked) {
            var group = "input:checkbox[id='"+$(this).attr("id")+"']";
            $(group).prop("checked", false);
            $(this).prop("checked",true);
        }
    });
</script>
@stop