<?php

Route::prefix('results')->group(function () {
    Route::post('/', \App\Http\Controllers\Result\StoreController::class);
    Route::get('top', \App\Http\Controllers\Result\TopListController::class);
});
