<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ManagersController;

Route::name('api.')->group(function () {
    Route::get('managers', [ManagersController::class, 'index'])->name('managers.index');
});
