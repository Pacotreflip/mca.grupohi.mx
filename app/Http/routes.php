<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Rutas de Paginas
Route::get('/', 'PagesController@home')->name('home');
Route::get('index', 'PagesController@index')->name('index');
Route::get('proyectos', 'PagesController@proyectos')->name('proyectos');
Route::get('origenes_usuarios', 'PagesController@origenes_usuarios')->name('origenes_usuarios.index');


// Rutas de Autenticación
Route::get('auth/login', [
        'as' => 'auth.login',
        'uses' => 'Auth\AuthController@getLogin'
    ]);

    Route::post('auth/login', [
        'as' => 'auth.login',
        'uses' => 'Auth\AuthController@postLogin'
    ]);

    Route::get('auth/logout', [
        'as' => 'auth.logout',
        'uses' => 'Auth\AuthController@getLogout'
    ]);

// Rutas de contexto...
Route::get('/context/{databaseName}/{id}', 'ContextController@set')
    ->name('context.set')
    ->where(['databaseName' => '[aA-zZ0-9_-]+', 'id' => '[0-9]+']);


// Rutas de Catalogos
Route::resource('materiales', 'MaterialesController');
Route::resource('marcas', 'MarcasController');
Route::resource('sindicatos', 'SindicatosController');
Route::resource('origenes', 'OrigenesController');
Route::get('origenes/{origenes}/tiros', 'OrigenesTirosController@index')->name('origenes.tiros.index');
Route::resource('tiros', 'TirosController');
Route::resource('rutas', 'RutasController');
Route::resource('ruta.archivos', 'RutaArchivosController');
Route::resource('camiones', 'CamionesController');
Route::resource('camion.imagenes', 'CamionImagenesController');
Route::resource('tarifas_material', 'TarifasMaterialController');
Route::resource('tarifas_peso', 'TarifasPesoController');
Route::resource('tarifas_tiporuta', 'TarifasTipoRutaController');
Route::resource('operadores', 'OperadoresController');
Route::resource('empresas', 'EmpresasController');
Route::resource('fda_material', 'FDAMaterialController');
Route::resource('fda_banco_material', 'FDABancoMaterialController');
Route::resource('etapas', 'EtapasController');

//Rutas de centros de costo
Route::get('centroscostos', 'CentrosCostosController@index')->name('centroscostos.index');
Route::post('centroscostos', 'CentrosCostosController@store')->name('centroscostos.store');
Route::get('centroscostos/create/{IdPadre}', 'CentrosCostosController@create')->name('centroscostos.create');
Route::get('centroscostos/{centroscostos}/edit', 'CentrosCostosController@edit')->name('centroscostos.edit');
Route::patch('centroscostos/{centroscostos}', 'CentrosCostosController@update')->name('centroscostos.update');
Route::delete('centroscostos/{centroscostos}', 'CentrosCostosController@destroy')->name('centroscostos.destroy');

//Rutas de Origenes por Usuario
Route::get('usuarios/{usuarios}/origenes', 'OrigenesUsuariosController@index');
Route::post('usuarios/{usuarios}/origenes/{origenes}', 'OrigenesUsuariosController@store');
Route::get('usuarios', 'UserController@index');

//Rutas de Viajes Netos
Route::get('viajes/manual/index', 'ViajesNetosController@index')->name('viajes.manual.index');
Route::get('viajes/manual/create', 'ViajesNetosController@create')->name('viajes.manual.create');
Route::get('viajes/manual/completa', 'ViajesNetosController@completa')->name('viajes.manual.completa');
Route::post('viajes/manual', 'ViajesNetosController@store')->name('viajes.manual.store');
Route::get('viajes/manual/autorizar' , 'ViajesNetosController@edit')->name('viajes.manual.edit');
Route::patch('viajes/manual', 'ViajesNetosController@update')->name('viajes.manual.update');