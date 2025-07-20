<?php

Route::namespace('App\Http\Controllers\API')->as('api.')->group(function () {

    Route::namespace('Friends')
        ->middleware('auth:sanctum')
        ->prefix('friends')
        ->as('friends.')
        ->group(function () {
            //
        });

    Route::namespace('User')
        ->middleware('auth:sanctum')
        ->prefix('user')
        ->as('user.')
        ->group(function (){
            //
        });

});
