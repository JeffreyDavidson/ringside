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
    Route::post('/wrestlers/{wrestler}/retire', 'RetiredWrestlersController@store')->name('wrestler.retire');
    Route::delete('/wrestlers/{wrestler}/unretire', 'RetiredWrestlersController@destroy')->name('wrestler.unretire');
    Route::post('/retired-wrestlers', 'RetiredWrestlersController@index')->name('retired-wrestlers.index');
    Route::post('/wrestlers/{wrestler}/suspend', 'SuspendedWrestlersController@store')->name('wrestler.suspend');
    Route::post('/suspended-wrestlers', 'SuspendedWrestlersController@index')->name('suspended-wrestlers.index');
    Route::delete('/wrestlers/{wrestler}/reinstate', 'SuspendedWrestlersController@destroy')->name('wrestler.reinstate');
    Route::post('/wrestlers/{wrestler}/injure', 'InjuredWrestlersController@store')->name('wrestler.injure');
    Route::post('/injured-wrestlers', 'InjuredWrestlersController@index')->name('injured-wrestlers.index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
