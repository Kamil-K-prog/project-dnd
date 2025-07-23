<?php

Route::namespace('App\Http\Controllers\API')->middleware('auth:sanctum')->as('api.')->group(function () {

    // Дружба
    Route::namespace('Friends')
        ->prefix('friends')
        ->as('friends.')
        ->group(function () {

            // Запросы в друзья
            Route::namespace('Requests')
                ->prefix('requests')
                ->as('requests.')
                ->group(function () {
                    Route::get('/', IndexController::class)->name('index');
                    Route::post('/', StoreController::class)->name('store');
                    Route::post('/{friendRequest}/accept', AcceptController::class)->name('accept');
                    Route::post('/{friendRequest}/decline', DeclineController::class)->name('decline');
                    Route::delete('/{friendRequest}', DestroyController::class)->name('destroy');
                });
        });


    // Пользователи
    Route::namespace('User')
        ->prefix('user')
        ->as('user.')
        ->group(function () {
            //
        });

});
