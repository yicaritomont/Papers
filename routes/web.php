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
    Route::get('user/{company?}', 'UserController@index')->name('users.company');
    Route::get('inspector/{company?}', 'InspectorController@index')->name('inspectors.company');
    Route::get('inspectoragenda/{view}', 'InspectorAgendaController@index')->name('inspectoragendas.view');
    Route::get('inspectoragendas/{id}', 'InspectorAgendaController@inspector')->name('inspectoragendas.inspector');
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
