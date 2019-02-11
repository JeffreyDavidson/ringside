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
    Route::post('/wrestlers/{wrestler}/retore', 'RetiredWrestlersController@store')->name('wrestler.retire');
    Route::post('/retired-wrestlers', 'RetiredWrestlersController@index')->name('retired-wrestlers.index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
