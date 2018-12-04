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

    //Eventos Calendario
    Route::get('inspectionappointments/events/{id?}', 'InspectionAppointmentController@events')->name('inspectionappointments.events');
    Route::get('inspectoragendas/events/{id?}', 'InspectorAgendaController@events')->name('inspectoragendas.events');

    //Completar las citas
    Route::put('inspectionappointments/{inspectionappointment}/complete', 'InspectionAppointmentController@complete')->name('inspectionappointments.complete');

    //Formato de citas
    Route::post('inspectionappointments/{inspectionappointment}/format', 'InspectionAppointmentController@format')->name('inspectionappointments.format');

    //Actualización de campos desplegables
    Route::get('inspectiontypes/subtypes/{id?}', 'InspectionTypeController@subtypes')->name('inspectionappointments.subtypes');
    Route::get('country/cities/{id?}', 'GeneralController@cities')->name('general.cities');
    Route::get('companies/clients/{company?}', 'CompanyController@clients')->name('company.clients');

    // ????
    Route::post('inspectionappointments/create', 'InspectionAppointmentController@create')->name('inspectionappointments.create.post');

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
    //Route::get('formats/informationFormat','FormatController@informationFormat')->name('formats.informationFormat');
    Route::get('ajxllenarCabeceraFormato','FormatController@llenarCabeceraFormato');
    Route::get('ajxcargarSelectClients','FormatController@cargarSelectClients');
    Route::get('/formats/downloadPDF/{id}','FormatController@downloadPDF');
    Route::resource('clients', 'ClientController');
    Route::resource('headquarters', 'HeadquartersController');
    Route::resource('companies', 'CompanyController');
    Route::resource('inspectionappointments', 'InspectionAppointmentController');
    Route::resource('inspectoragendas', 'InspectorAgendaController');

    Route::get('ajxVerifyInspector','InspectorController@VerifyInspector');
    Route::resource('contracts', 'ContractController');





    //Supports
    Route::post('supports/upload','FormatController@upload')->name('support.upload');
    Route::get('formats/supports/{id}','FormatController@supports')->name('formats.supports');
    Route::post('supports/get','FormatController@getInitialData')->name('get.initData');
    Route::post('supports/delete','FormatController@delete')->name('supports.delete');


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
