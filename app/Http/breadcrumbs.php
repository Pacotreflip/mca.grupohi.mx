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

//Catalogos->Tiros

Breadcrumbs::register('tiros.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.tiros')), route('tiros.index'));
});

Breadcrumbs::register('tiros.show', function($breadcrumbs, $tiro){
    $breadcrumbs->parent('tiros.index');
    $breadcrumbs->push(strtoupper($tiro->Descripcion), route('tiros.show', $tiro));
});

Breadcrumbs::register('tiros.create', function($breadcrumbs){
    $breadcrumbs->parent('tiros.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_tiro')), route('tiros.create'));
});

Breadcrumbs::register('tiros.edit', function($breadcrumbs, $tiro){
    $breadcrumbs->parent('tiros.show', $tiro);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('tiros.edit', $tiro));
});

//Catalogos->Rutas

Breadcrumbs::register('rutas.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.rutas')), route('rutas.index'));
});

Breadcrumbs::register('rutas.show', function($breadcrumbs, $ruta){
    $breadcrumbs->parent('rutas.index');
    $breadcrumbs->push(strtoupper($ruta->present()->clave), route('rutas.show', $ruta));
});

Breadcrumbs::register('rutas.create', function($breadcrumbs){
    $breadcrumbs->parent('rutas.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_ruta')), route('rutas.create'));
});

Breadcrumbs::register('rutas.edit', function($breadcrumbs, $ruta){
    $breadcrumbs->parent('rutas.show', $ruta);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('rutas.edit', $ruta));
});

//Catalogos->Camiones

Breadcrumbs::register('camiones.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.camiones')), route('camiones.index'));
});

Breadcrumbs::register('camiones.show', function($breadcrumbs, $camion){
    $breadcrumbs->parent('camiones.index');
    $breadcrumbs->push($camion->present()->datos, route('camiones.show', $camion));
});

Breadcrumbs::register('camiones.create', function($breadcrumbs){
    $breadcrumbs->parent('camiones.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_camion')), route('camiones.create'));
});

Breadcrumbs::register('camiones.edit', function($breadcrumbs, $camion){
    $breadcrumbs->parent('camiones.show', $camion);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('camiones.edit', $camion));
});

//Catalogos->Tarifas por Material

Breadcrumbs::register('tarifas_material.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.tarifas_material')), route('tarifas_material.index'));
});

//Catalogos->Tarifas por Peso

Breadcrumbs::register('tarifas_peso.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.tarifas_peso')), route('tarifas_peso.index'));
});

//Catalogos->Tarifas por Tipo de Ruta

Breadcrumbs::register('tarifas_tiporuta.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.tarifas_tiporuta')), route('tarifas_tiporuta.index'));
});

//Catalogos->Materiales

Breadcrumbs::register('operadores.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.operadores')), route('operadores.index'));
});

Breadcrumbs::register('operadores.show', function($breadcrumbs, $operador){
    $breadcrumbs->parent('operadores.index');
    $breadcrumbs->push(strtoupper($operador->Nombre), route('operadores.show', $operador));
});

Breadcrumbs::register('operadores.create', function($breadcrumbs){
    $breadcrumbs->parent('operadores.index');
    $breadcrumbs->push(strtoupper(trans('strings.new_operador')), route('operadores.create'));
});

Breadcrumbs::register('operadores.edit', function($breadcrumbs, $operador){
    $breadcrumbs->parent('operadores.show', $operador);
    $breadcrumbs->push(strtoupper(trans('strings.edit')), route('operadores.edit', $operador));
});

//Catálogos->centros_costo

Breadcrumbs::register('centroscostos.index', function($breadcrumbs){
    $breadcrumbs->push(strtoupper(trans('strings.centroscostos')), route('centroscostos.index'));
});