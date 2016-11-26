<?php

//Catalogos->Materiales

Breadcrumbs::register('materiales.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.materials')), route('materiales.index'));
});

Breadcrumbs::register('materiales.show', function($breadcrumbs, $material){
    $breadcrumbs->parent('materiales.index');
    $breadcrumbs->push(strtoupper($material->Descripcion), route('materiales.show', $material));
});

Breadcrumbs::register('materiales.create', function($breadcrumbs){
    $breadcrumbs->parent('materiales.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_material')), route('materiales.create'));
});

Breadcrumbs::register('materiales.edit', function($breadcrumbs, $material){
    $breadcrumbs->parent('materiales.show', $material);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('materiales.edit', $material));
});

//Catalogos->Marcas

Breadcrumbs::register('marcas.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.brands')), route('marcas.index'));
});

Breadcrumbs::register('marcas.show', function($breadcrumbs, $marca){
    $breadcrumbs->parent('marcas.index');
    $breadcrumbs->push(strtoupper($marca->Descripcion), route('marcas.show', $marca));
});

Breadcrumbs::register('marcas.create', function($breadcrumbs){
    $breadcrumbs->parent('marcas.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_brand')), route('marcas.create'));
});

Breadcrumbs::register('marcas.edit', function($breadcrumbs, $marca){
    $breadcrumbs->parent('marcas.show', $marca);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('marcas.edit', $marca));
});

//Catalogos->Sindicatos

Breadcrumbs::register('sindicatos.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.sindicatos')), route('sindicatos.index'));
});

Breadcrumbs::register('sindicatos.show', function($breadcrumbs, $sindicato){
    $breadcrumbs->parent('sindicatos.index');
    $breadcrumbs->push(strtoupper($sindicato->NombreCorto), route('sindicatos.show', $sindicato));
});

Breadcrumbs::register('sindicatos.create', function($breadcrumbs){
    $breadcrumbs->parent('sindicatos.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_sindicato')), route('sindicatos.create'));
});

Breadcrumbs::register('sindicatos.edit', function($breadcrumbs, $sindicato){
    $breadcrumbs->parent('sindicatos.show', $sindicato);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('sindicatos.edit', $sindicato));
});

//Catalogos->Origenes

Breadcrumbs::register('origenes.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.origins')), route('origenes.index'));
});

Breadcrumbs::register('origenes.show', function($breadcrumbs, $origen){
    $breadcrumbs->parent('origenes.index');
    $breadcrumbs->push(strtoupper($origen->Descripcion), route('origenes.show', $origen));
});

Breadcrumbs::register('origenes.create', function($breadcrumbs){
    $breadcrumbs->parent('origenes.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_origin')), route('origenes.create'));
});

Breadcrumbs::register('origenes.edit', function($breadcrumbs, $origen){
    $breadcrumbs->parent('origenes.show', $origen);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('origenes.edit', $origen));
});

//Catalogos->Destinos

Breadcrumbs::register('destinos.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.destinos')), route('destinos.index'));
});

Breadcrumbs::register('destinos.show', function($breadcrumbs, $destino){
    $breadcrumbs->parent('destinos.index');
    $breadcrumbs->push(strtoupper($destino->Descripcion), route('destinos.show', $destino));
});

Breadcrumbs::register('destinos.create', function($breadcrumbs){
    $breadcrumbs->parent('destinos.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_destino')), route('destinos.create'));
});

Breadcrumbs::register('destinos.edit', function($breadcrumbs, $destino){
    $breadcrumbs->parent('destinos.show', $destino);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('destinos.edit', $destino));
});