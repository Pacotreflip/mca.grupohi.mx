@extends('layout')

@section('content')
    <h1>CONFIGURACIÓN DE TELÉFONOS
        <a href="{{ route('telefonos.create') }}" class="btn btn-success pull-right" ><i class="fa fa-plus"></i> Nuevo Teléfono </a>
    </h1>
    {!! Breadcrumbs::render('telefonos.index') !!}
    <hr>
     <div class="table-responsive">
         <table class="table table-hover table-striped small">
             <thead>
             <tr>
                 <th>ID</th>
                 <th>IMEI Teléfono</th>
                 <th>MAC Address Impresora</th>
                 <th>Acciones</th>
             </tr>
             </thead>
             <tbody>
             @foreach($telefonos as $telefono)
                 <tr>
                     <td>{{ $telefono->id }}</td>
                     <td>{{ $telefono->imei }}</td>
                     <td>{{ $telefono->impresora }}</td>
                     <td>
                         {!! Form::open(['route' => ['telefonos.destroy', $telefono], 'method' => 'delete']) !!}
                         <a href="{{ route('telefonos.show', $telefono) }}" title="Ver" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                         <a href="{{ route('telefonos.edit', $telefono) }}" title="Editar" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>
                         <button type="submit" title="Eliminar" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></button>
                         {!! Form::close() !!}
                     </td>
                 </tr>
             @endforeach
             </tbody>
         </table>
     </div>

@endsection
