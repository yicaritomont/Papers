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

Route::get('ajxVerifyPassword','PerfilController@VerifyPassword');

Route::group( ['middleware' => ['auth']], function() {
    Route::get('users/company/{company?}', 'UserController@index')->name('users.company');
    Route::get('inspectors/company/{company?}', 'InspectorController@index')->name('inspectors.company');
    Route::get('inspectoragendas/list', 'InspectorAgendaController@list')->name('inspectoragendas.view');
    Route::get('inspectoragendas/inspector/{id}/{view}', 'InspectorAgendaController@inspector')->name('inspectoragendas.inspector');
    Route::post('inspectoragendas/ajax', 'InspectorAgendaController@storeAjax')->name('inspectoragendas.store.ajax');
    Route::put('inspectoragendas/ajax/{inspectoragenda}', 'InspectorAgendaController@updateAjax')->name('inspectoragendas.update.ajax');
    Route::delete('inspectoragendas/ajax/{inspectoragenda}', 'InspectorAgendaController@destroyAjax')->name('inspectoragendas.destroy.ajax');
    Route::get('inspectionappointments/events', 'InspectionAppointmentController@events')->name('inspectionappointments.events');
    Route::post('inspectionappointments/subtypes', 'InspectionAppointmentController@subtypes')->name('inspectionappointments.subtypes');
    Route::post('inspectoragendas/cities', 'InspectorAgendaController@cities')->name('inspectoragendas.cities');
    Route::post('inspectionappointments/create', 'InspectionAppointmentController@create')->name('inspectionappointments.create.post');
    Route::get('inspectionappointments/inspector/{id?}', 'InspectionAppointmentController@inspector')->name('inspectionappointments.inspector');
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
    Route::resource('clients', 'ClientController');
    Route::resource('headquarters', 'HeadquartersController');
    Route::resource('companies', 'CompanyController');
    Route::resource('inspectionappointments', 'InspectionAppointmentController');
    Route::resource('inspectoragendas', 'InspectorAgendaController');
    Route::resource('contracts', 'ContractController');

    Route::get('datatable/{model}/{entity}/{identificador?}/{relations?}', 'GeneralController@datatable')->name('datatable');
});


Route::get('lang/{lang}', function($lang) {
    Session::put('lang', $lang);
    return Redirect::back();
  })->middleware('web')->name('change_lang');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
