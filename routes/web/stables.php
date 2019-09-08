<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stables\StablesController;
use App\Http\Controllers\Stables\RestoreController;
use App\Http\Controllers\Stables\RetireController;
use App\Http\Controllers\Stables\UnretireController;
use App\Http\Controllers\Stables\EmployController;
use App\Http\Controllers\Stables\DisassembleController;

Route::resource('stables', StablesController::class);
Route::put('/roster/stables/{stable}/restore', RestoreController::class)->name('stables.restore');
Route::put('/roster/stables/{stable}/retire', RetireController::class)->name('stables.retire');
Route::put('/roster/stables/{stable}/unretire', UnretireController::class)->name('stables.unretire');
Route::put('/roster/stables/{stable}/employ', EmployController::class)->name('stables.employ');
Route::put('/roster/stables/{stable}/disassemble', DisassembleController::class)->name('stables.disassemble');
