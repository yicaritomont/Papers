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

Route::group( ['middleware' => ['auth']], function() {
    Route::get('users/company/{company?}', 'UserController@index')->name('users.company');
    Route::get('inspectors/company/{company?}', 'InspectorController@index')->name('inspectors.company');
    Route::get('inspectoragendas/list', 'InspectorAgendaController@list')->name('inspectoragendas.view');
    Route::get('inspectoragendas/inspector/{id}/{view}', 'InspectorAgendaController@inspector')->name('inspectoragendas.inspector');
    Route::post('inspectoragendas/ajax', 'InspectorAgendaController@storeAjax')->name('inspectoragendas.store.ajax');
    Route::put('inspectoragendas/ajax/{inspectoragenda}', 'InspectorAgendaController@updateAjax')->name('inspectoragendas.update.ajax');
    Route::delete('inspectoragendas/ajax/{inspectoragenda}', 'InspectorAgendaController@destroyAjax')->name('inspectoragendas.destroy.ajax');
    Route::get('inspectoragendas/events', 'InspectorAgendaController@events')->name('inspectoragendas.events');
    Route::get('inspectionappointments/events', 'InspectionAppointmentController@events')->name('inspectionappointments.events');
    Route::post('inspectionappointments/subtypes', 'InspectionAppointmentController@subtypes')->name('inspectionappointments.subtypes');
    Route::post('inspectionappointments/create', 'InspectionAppointmentController@create')->name('inspectionappointments.create.post');
    Route::get('inspectionappointments/inspector/{id?}', 'InspectionAppointmentController@inspector')->name('inspectionappointments.inspector');
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('posts', 'PostController');
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
});

Route::get('lang/{lang}', function($lang) {
    Session::put('lang', $lang);
    return Redirect::back();
  })->middleware('web')->name('change_lang');
