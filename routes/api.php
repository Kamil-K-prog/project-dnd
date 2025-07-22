<?php

Route::namespace('App\Http\Controllers\API')->middleware('auth:sanctum')->as('api.')->group(function () {

    Route::namespace('Friends')
        ->prefix('friends')
        ->as('friends.')
        ->group(function () {
            Route::namespace('Requests')
                ->prefix('requests')
                ->as('requests.')
                ->group(function () {
                    Route::get('/', IndexController::class)->name('index');
                    Route::post('/', StoreController::class)->name('store');
                });
        });

    Route::namespace('User')
        ->prefix('user')
        ->as('user.')
        ->group(function () {
            //
        });

});
