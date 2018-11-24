<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* DB::listen(function($query){
    echo "<pre>{$query->sql}</pre>";
}); */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Password Reset
Route::resource('reminder', 'RemindersController');
Route::get('reminder',['as'=>'reminder','uses' => 'RemindersController@getRemind']);
Route::post('postRemind',['as'=>'postRemind','uses' => 'RemindersController@postRemind']);
Route::get('validateInspector/{id}', ['as' =>'validateInspector','uses' => 'InspectorController@IdCardInspector' ]);

Route::get('ajxVerifyPassword','PerfilController@VerifyPassword');
// cargar ciudades al pais seleccionado
Route::get('ajxCountry','InspectorController@asincronia');

Route::group( ['middleware' => ['auth']], function() {

    // Rutas para la eleccion de inicio de session
    Route::get('elegirCompania', array('as' => 'elegirCompania', 'uses'=>'UserController@ShowMultiple'));
    Route::get('enviaCompania/{id}',array('as'=>'enviaCompania','uses'=>'UserController@PostMultiple'));

    // Consultar los usuarios de una compañia
    Route::get('user/{company?}', 'UserController@index')->name('users.company');
    
    //Consultar los usuarios de una compañia D?
    Route::get('users/company/{company?}', 'UserController@index')->name('users.company');

    //Consultar los inspectores de una compañia D?
    Route::get('inspectors/company/{company?}', 'InspectorController@index')->name('inspectors.company');

    //Vista tabla de agendas D?
    Route::get('inspectoragendas/list', 'InspectorAgendaController@list')->name('inspectoragendas.view');

    //Consultar agendas de un inspector y en una vista dada D?
    // Route::get('inspectoragendas/inspector/{id}/{view}', 'InspectorAgendaController@inspector')->name('inspectoragendas.inspector');

    //Acciones ajax de agendas
    Route::post('inspectoragendas/ajax', 'InspectorAgendaController@storeAjax')->name('inspectoragendas.store.ajax');
    Route::put('inspectoragendas/ajax/{inspectoragenda}', 'InspectorAgendaController@updateAjax')->name('inspectoragendas.update.ajax');
    Route::delete('inspectoragendas/ajax/{inspectoragenda}', 'InspectorAgendaController@destroyAjax')->name('inspectoragendas.destroy.ajax');
    
    //Eventos Calendario
    Route::get('inspectionappointments/events', 'InspectionAppointmentController@events')->name('inspectionappointments.events');
    Route::get('inspectoragendas/events', 'InspectorAgendaController@events')->name('inspectoragendas.events');

    //Consultar las agendas de un inspector
    // Route::get('inspectoragendas/{id}', 'InspectorAgendaController@inspector')->name('inspectoragendas.inspector');
    
    //Completar las citas
    Route::put('inspectionappointments/{inspectionappointment}/complete', 'InspectionAppointmentController@complete')->name('inspectionappointments.complete');
    
    //Actualización de campos desplegables
    Route::post('inspectionappointments/subtypes', 'InspectionAppointmentController@subtypes')->name('inspectionappointments.subtypes');
    Route::post('inspectoragendas/cities', 'InspectorAgendaController@cities')->name('inspectoragendas.cities');
    
    // ????
    Route::post('inspectionappointments/create', 'InspectionAppointmentController@create')->name('inspectionappointments.create.post');
    
    //Consultar las citas de un inspector
    Route::get('inspectionappointments/inspector/{id?}', 'InspectionAppointmentController@inspector')->name('inspectionappointments.inspector');
    
    //Consultar datos para dataTable
    Route::get('datatable/{model}/{relations?}/{entity?}/{identificador?}', 'GeneralController@datatable')->name('datatable');
    
    //Consultar los usuarios de una compañia para dataTable
    Route::get('users/companyTable/{company}', 'UserController@companyTable')->name('users.companyTable');
    
    //Consultar los inspectores de una compañia para dataTable
    Route::get('inspectors/companyTable/{company}', 'InspectorController@companyTable')->name('inspectors.companyTable');
    
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('posts', 'PostController');
    Route::resource('permissions','PermissionController');
    Route::resource('modulos','ModuloController');
    Route::resource('menus','MenuController');
    Route::resource('perfiles','PerfilController');
    Route::resource('agendas','MenuController');
    Route::post('changePassword/{id}',['as'=>'changePassword','uses' => 'PerfilController@changePassword']);

    Route::resource('clients', 'ClientController');
    Route::resource('headquarters', 'HeadquartersController');
    Route::resource('companies', 'CompanyController');
    Route::resource('inspectors','InspectorController');
    Route::resource('inspectortypes','InspectorTypeController');
    Route::resource('professions','ProfessionController');
    Route::resource('inspectiontypes','InspectionTypeController');
    Route::resource('inspectionsubtypes','InspectionSubtypeController');
    Route::resource('preformatos','PreformatoController');
    Route::resource('formats','FormatController');
    Route::get('formats/informationFormat','FormatController@informationFormat')->name('formats.informationFormat');
    Route::get('ajxllenarCabeceraFormato','FormatController@llenarCabeceraFormato');
    Route::get('ajxcargarSelectClients','FormatController@cargarSelectClients');
    Route::resource('clients', 'ClientController');
    Route::resource('headquarters', 'HeadquartersController');
    Route::resource('companies', 'CompanyController');
    Route::resource('inspectionappointments', 'InspectionAppointmentController');
    Route::resource('inspectoragendas', 'InspectorAgendaController');

    Route::get('ajxVerifyInspector','InspectorController@VerifyInspector');
    Route::resource('contracts', 'ContractController');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * Ruta exclusiva para el manejo del lenguaje
 */
Route::get('lang/{lang}', function($lang) {
    Session::put('lang', $lang);
    return Redirect::back();
  })->middleware('web')->name('change_lang');
