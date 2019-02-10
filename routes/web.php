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

Route::middleware(['middleware' => 'auth'])->group(function() {
    Route::post('/wrestlers', 'WrestlersController@store');
    Route::get('/wrestlers/new', 'WrestlersController@create');
    Route::get('/wrestlers/{wrestler}/edit', 'WrestlersController@edit');
    Route::patch('/wrestlers/{wrestler}', 'WrestlersController@update');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
