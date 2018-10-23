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
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('posts', 'PostController');
    Route::resource('clients', 'ClientController');
    Route::resource('headquarters', 'HeadquartersController');
    Route::resource('companies', 'CompanyController');
});

Route::get('lang/{lang}', function($lang) {
    Session::put('lang', $lang);
    return Redirect::back();
  })->middleware('web')->name('change_lang');
