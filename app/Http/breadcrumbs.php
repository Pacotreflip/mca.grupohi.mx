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