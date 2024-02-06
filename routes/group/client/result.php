<?php

use App\Http\Controllers\Result\StoreController;
use App\Http\Controllers\Result\TopListController;
use Illuminate\Support\Facades\Route;

Route::prefix('results')->group(function () {
    Route::post('/', StoreController::class);
    Route::get('top', TopListController::class);
});
