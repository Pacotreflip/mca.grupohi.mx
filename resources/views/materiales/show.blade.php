@extends('layout')

@section('content')
<h1>{{ $material->Descripcion }}
    <a href="{{ route('materiales.edit', $material) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> {{ trans('strings.edit_material') }}</a>
</h1>
{!! Breadcrumbs::render('materiales.show', $material) !!}
<hr>
@stop