@extends('layout')

@section('content')
    <h1>CINGIGURACIÓN DE TELÉFONOS E IMPRESORAS
        <a href="{{ route('telefonos-impresoras.create') }}" class="btn btn-success pull-right" ><i class="fa fa-plus"></i> Nueva Configuración </a>
    </h1>
    {!! Breadcrumbs::render('telefonos-impresoras.index') !!}
    <hr>
    <div class="panel-group" id="accordion">
        @foreach($configuraciones as $configuracion)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Collapsible Group 1</a>
                </h4>
            </div>
            <div id="collapse1" class="panel-collapse collapse in">
                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
            </div>
        </div>
        @endforeach
    </div>

@endsection