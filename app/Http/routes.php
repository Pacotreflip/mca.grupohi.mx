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
Route::get('viajes_netos', 'ViajesNetosController@index')->name('viajes_netos.index');
Route::get('viajes_netos/create', 'ViajesNetosController@create')->name('viajes_netos.create');
Route::group(['prefix' => 'viajes_netos'], function() {
    Route::post('completa', 'ViajesNetosController@store');
    Route::post('manual', [
        'as' => 'viajes_netos.manual.store',
        'uses' => 'ViajesNetosController@store'
    ]);
});
Route::get('viajes_netos/edit' , 'ViajesNetosController@edit')->name('viajes_netos.edit');
Route::patch('viajes_netos', 'ViajesNetosController@update')->name('viajes_netos.update');
Route::group(['prefix' => 'viajes_netos'], function() {
    Route::patch('autorizar', 'ViajesNetosController@update')->name('viajes_netos.autorizar');
});
Route::get('viajes_netos/{viaje_neto}', 'ViajesNetosController@show')->name('viajes_netos.show');

//PDF Routes
Route::group(['prefix' => 'pdf'], function () {

    Route::get('conciliacion/{id}', [
        'as'   => 'pfd.conciliacion',
        'uses' => 'PDFController@conciliacion'
    ]);

    Route::get('viajes_netos', [
        'as' => 'pdf.viajes_netos',
        'uses' => 'PDFController@viajes_netos'
    ]);

    Route::get('corte/{corte}', [
        'as' => 'pdf.corte',
        'uses' => 'PDFController@corte'
    ]);
    
    Route::get('viajes_netos_conflicto', [
        'as' => 'pdf.viajes_netos_conflicto',
        'uses' => 'PDFController@viajes_netos_conflicto'
    ]);
});

//XLS Routes
Route::group(['prefix' => 'xls'], function () {

    Route::get('conciliacion/{id}', [
        'as'   => 'xls.conciliacion',
        'uses' => 'XLSController@conciliacion'
    ]);
    Route::get('conciliaciones', [
        'as'   => 'xls.conciliaciones',
        'uses' => 'XLSController@conciliaciones'
    ]);

});

//Reportes Routes
Route::group(['prefix' => 'reportes'], function () {
    Route::get('viajes_netos/create', [
        'as'   => 'reportes.viajes_netos.create',
        'uses' => 'ReportesController@viajes_netos_create'
    ]);
    Route::get('viajes_netos/show', [
        'as'   => 'reportes.viajes_netos.show',
        'uses' => 'ReportesController@viajes_netos_show'
    ]);
});

Route::resource('conciliaciones', 'ConciliacionesController');

Route::post('conciliacion/{conciliacion}/detalles', 'ConciliacionesDetallesController@store')->name('conciliaciones.detalles.store');
Route::get('conciliacion/{conciliacion}/detalles', 'ConciliacionesDetallesController@index')->name('conciliaciones.detalles.index');
Route::delete('conciliacion/{conciliacion}/detalles/{detalle}', 'ConciliacionesDetallesController@destroy')->name('conciliaciones.detalles.destroy');
Route::get('conciliacion_info_carga/{filename}', 'ConciliacionesDetallesController@detalle_carga')->name('conciliacion.info');

Route::get('viajes', 'ViajesController@index')->name('viajes.index');
Route::patch('viajes/{viaje}', 'ViajesController@update');
Route::get('viajes/edit', 'ViajesController@edit')->name('viajes.edit');

//Rutas de corte de checador
Route::get('corte/create', 'CorteController@create')->name('corte.create');
Route::post('corte', 'CorteController@store')->name('corte.store');
Route::get('corte/{corte}', 'CorteController@show')->name('corte.show');
Route::get('corte', 'CorteController@index')->name('corte.index');
Route::get('corte/{corte}/edit', 'CorteController@edit')->name('corte.edit');
Route::patch('corte/{corte}', 'CorteController@update')->name('corte.update');

Route::get('corte/{corte}/viajes_netos', 'CorteViajesController@index')->name('corte.viajes_netos.index');
Route::patch('corte/{corte}/viajes_netos/{viaje_neto}', 'CorteViajesController@update')->name('corte.viajes_netos.update');


//Rutas de Configuración Diaria
Route::get('configuracion-diaria', 'ConfiguracionDiariaController@index')->name('configuracion-diaria.index');
Route::post('configuracion-diaria', 'ConfiguracionDiariaController@store');
Route::delete('configuracion-diaria/{id}', 'ConfiguracionDiariaController@destroy');

//Rutas de Roles y Permisos
Route::resource('user.roles', 'UserRolesController');

//Rutas de Administración
Route::group(['prefix' => 'administracion', 'middleware' => ['role:administrador-sistema']], function () {
    Route::get('/', 'PagesController@administracion')->name('administracion');
});

Route::group(['prefix' => 'csv'],function () {
    Route::get('rutas', 'CSVController@rutas')->name('csv.rutas');
    Route::get('origenes', 'CSVController@origenes')->name('csv.origenes');
});
