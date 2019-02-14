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
    Route::resource('wrestler', 'WrestlersController');
    Route::patch('/wrestlers/{wrestler}/restore', 'WrestlersController@restore')->name('wrestler.restore');
    Route::post('/wrestlers/{wrestler}/retire', 'RetirementsController@store')->name('wrestler.retire');
    Route::delete('/wrestlers/{wrestler}/unretire', 'RetirementsController@destroy')->name('wrestler.unretire');
    Route::get('/retired-wrestlers', 'RetirementsController@index')->name('retired-wrestlers.index');
    Route::post('/wrestlers/{wrestler}/suspend', 'SuspensionsController@store')->name('wrestler.suspend');
    Route::get('/suspended-wrestlers', 'SuspensionsController@index')->name('suspended-wrestlers.index');
    Route::delete('/wrestlers/{wrestler}/reinstate', 'SuspensionsController@destroy')->name('wrestler.reinstate');
    Route::post('/wrestlers/{wrestler}/injure', 'InjuriesController@store')->name('wrestler.injure');
    Route::get('/injured-wrestlers', 'InjuriesController@index')->name('injured-wrestlers.index');
    Route::delete('/wrestlers/{wrestler}/recover', 'InjuriesController@destroy')->name('wrestler.recover');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
