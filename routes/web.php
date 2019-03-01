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

Route::middleware(['middleware' => 'auth'])->group(function () {
    Route::get('/wrestlers/create', 'Wrestlers\WrestlersController@create')->name('wrestlers.create');
    Route::post('/wrestlers', 'Wrestlers\WrestlersController@store')->name('wrestlers.store');
    Route::get('/wrestlers/state/{state?}', 'Wrestlers\WrestlersController@index')->name('wrestlers.index');
    Route::get('/wrestlers/{wrestler}/edit', 'Wrestlers\WrestlersController@edit')->name('wrestlers.edit');
    Route::patch('/wrestlers/{wrestler}', 'Wrestlers\WrestlersController@update')->name('wrestlers.update');
    Route::delete('/wrestlers/{wrestler}', 'Wrestlers\WrestlersController@destroy')->name('wrestlers.destroy');
    Route::get('/wrestlers/{wrestler}', 'Wrestlers\WrestlersController@show')->name('wrestlers.show');
    Route::patch('/wrestlers/{wrestler}/restore', 'Wrestlers\WrestlersController@restore')->name('wrestlers.restore');
    Route::post('/wrestlers/{wrestler}/retire', 'Wrestlers\WrestlerRetirementsController@store')->name('wrestlers.retire');
    Route::delete('/wrestlers/{wrestler}/unretire', 'Wrestlers\WrestlerRetirementsController@destroy')->name('wrestlers.unretire');
    Route::delete('/wrestlers/{wrestler}/reinstate', 'Wrestlers\WrestlerSuspensionsController@destroy')->name('wrestlers.reinstate');
    Route::post('/wrestlers/{wrestler}/injure', 'Wrestlers\WrestlerInjuriesController@store')->name('wrestlers.injure');
    Route::delete('/wrestlers/{wrestler}/recover', 'Wrestlers\WrestlerInjuriesController@destroy')->name('wrestlers.recover');
    Route::post('/wrestlers/{wrestler}/deactivate', 'Wrestlers\WrestlerActivationsController@destroy')->name('wrestlers.deactivate');
    Route::post('/wrestlers/{wrestler}/activate', 'Wrestlers\WrestlerActivationsController@store')->name('wrestlers.activate');
    Route::post('/wrestlers/{wrestler}/suspend', 'Wrestlers\WrestlerSuspensionsController@store')->name('wrestlers.suspend');
    Route::get('/tag-teams/create', 'TagTeams\TagTeamsController@create')->name('tagteams.create');
    Route::post('/tag-teams', 'TagTeams\TagTeamsController@store')->name('tagteams.store');
    Route::get('/tag-teams/{tagteam}/edit', 'TagTeams\TagTeamsController@edit')->name('tagteams.edit');
    Route::patch('/tag-teams/{tagteam}', 'TagTeams\TagTeamsController@update')->name('tagteams.update');
    Route::delete('/tag-teams/{tagteam}', 'TagTeams\TagTeamsController@destroy')->name('tagteams.destroy');
    Route::patch('/tag-teams/{tagteam}/restore', 'TagTeams\TagTeamsController@restore')->name('tagteams.restore');
    Route::post('/tag-teams/{tagteam}/suspend', 'TagTeams\TagTeamSuspensionsController@store')->name('tagteams.suspend');
    Route::delete('/tag-teams/{tagteam}/reinstate', 'TagTeams\TagTeamSuspensionsController@destroy')->name('tagteams.reinstate');
    Route::post('/tag-teams/{tagteam}/deactivate', 'TagTeams\TagTeamActivationsController@destroy')->name('tagteams.deactivate');
    Route::post('/tag-teams/{tagteam}/activate', 'TagTeams\TagTeamActivationsController@store')->name('tagteams.activate');
    Route::post('/tag-teams/{tagteam}/retire', 'TagTeams\TagTeamRetirementsController@store')->name('tagteams.retire');
    Route::delete('/tag-teams/{tagteam}/unretire', 'TagTeams\TagTeamRetirementsController@destroy')->name('tagteams.unretire');
    Route::get('/tag-teams/state/{state?}', 'TagTeams\TagTeamsController@index')->name('tagteams.index');
    Route::get('/tag-teams/{tagteam}', 'TagTeams\TagTeamsController@show')->name('tagteams.show');
    Route::get('/managers/create', 'Managers\ManagersController@create')->name('managers.create');
    Route::post('/managers', 'Managers\ManagersController@store')->name('managers.store');
    Route::get('/managers/{manager}/edit', 'Managers\ManagersController@edit')->name('managers.edit');
    Route::patch('/managers/{manager}', 'Managers\ManagersController@update')->name('managers.update');
    Route::delete('/managers/{manager}', 'Managers\ManagersController@destroy')->name('managers.destroy');
    Route::patch('/managers/{manager}/restore', 'Managers\ManagersController@restore')->name('managers.restore');
    Route::post('/managers/{manager}/retire', 'Managers\ManagerRetirementsController@store')->name('managers.retire');
    Route::delete('/managers/{manager}/unretire', 'Managers\ManagerRetirementsController@destroy')->name('managers.unretire');
    Route::post('/managers/{manager}/injure', 'Managers\ManagerInjuriesController@store')->name('managers.injure');
    Route::delete('/managers/{manager}/recover', 'Managers\ManagerInjuriesController@destroy')->name('managers.recover');
    Route::post('/managers/{manager}/deactivate', 'Managers\ManagerActivationsController@destroy')->name('managers.deactivate');
    Route::post('/managers/{manager}/activate', 'Managers\ManagerActivationsController@store')->name('managers.activate');
    Route::post('/managers/{manager}/suspend', 'Managers\ManagerSuspensionsController@store')->name('managers.suspend');
    Route::delete('/managers/{manager}/reinstate', 'Managers\ManagerSuspensionsController@destroy')->name('managers.reinstate');
    Route::get('/managers/state/{state?}', 'Managers\ManagersController@index')->name('managers.index');
    Route::get('/managers/{manager}', 'Managers\ManagersController@show')->name('managers.show');
    Route::get('/referees/create', 'Referees\RefereesController@create')->name('referees.create');
    Route::post('/referees', 'Referees\RefereesController@store')->name('referees.store');
    Route::get('/referees/{referee}/edit', 'Referees\RefereesController@edit')->name('referees.edit');
    Route::patch('/referees/{referee}', 'Referees\RefereesController@update')->name('referees.update');
    Route::delete('/referees/{referee}', 'Referees\RefereesController@destroy')->name('referees.destroy');
    Route::patch('/referees/{referee}/restore', 'Referees\RefereesController@restore')->name('referees.restore');
    Route::get('/referees', 'Referees\RefereesController@index')-> name('referees.index');
    Route::post('/referees/{referee}/retire', 'Referees\RefereeRetirementsController@store')->name('referees.retire');
    Route::delete('/referees/{referee}/unretire', 'Referees\RefereeRetirementsController@destroy')->name('referees.unretire');
    Route::post('/referees/{referee}/injure', 'Referees\RefereeInjuriesController@store')->name('referees.injure');
    Route::delete('/referees/{referee}/recover', 'Referees\RefereeInjuriesController@destroy')->name('referees.recover');
    Route::post('/referees/{referee}/deactivate', 'Referees\RefereeActivationsController@destroy')->name('referees.deactivate');
    Route::post('/referees/{referee}/activate', 'Referees\RefereeActivationsController@store')->name('referees.activate');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
