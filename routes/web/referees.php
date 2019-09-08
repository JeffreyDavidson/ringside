<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Referees\RefereesController;
use App\Http\Controllers\Referees\InjureController;
use App\Http\Controllers\Referees\RetireController;
use App\Http\Controllers\Referees\RecoverController;
use App\Http\Controllers\Referees\RestoreController;
use App\Http\Controllers\Referees\EmployController;
use App\Http\Controllers\Referees\UnretireController;

Route::resource('referees', RefereesController::class);
Route::put('/referees/{referee}/restore', RestoreController::class)->name('referees.restore');
Route::put('/referees/{referee}/retire', RetireController::class)->name('referees.retire');
Route::put('/referees/{referee}/unretire', UnretireController::class)->name('referees.unretire');
Route::put('/referees/{referee}/injure', InjureController::class)->name('referees.injure');
Route::put('/referees/{referee}/recover', RecoverController::class)->name('referees.recover');
Route::put('/referees/{referee}/employ', EmployController::class)->name('referees.employ');
