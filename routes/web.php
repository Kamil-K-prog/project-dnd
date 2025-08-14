<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::namespace('App\Http\Controllers\Web')
    ->group(function () {

        Route::get('/', function () {
            return Inertia::render('Welcome', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        });

        // Группа контроллеров для dashboard
        Route::namespace('Dashboard')
            ->middleware(['auth', 'verified']) // Стандартные middleware для безопасности
            ->prefix('dashboard') // Эта группа контроллеров будет отвечать за адрес /dashboard
            ->as('dashboard.') // Эта строка будет подставляться в name каждого контроллера в этой группе
            ->group(function () { // Группируем
                Route::get('/', IndexController::class)->name('index'); // name будет dashboard.index
            });


        // Группа контроллеров для страницы друзей
        Route::namespace('Friends')
            ->middleware(['auth', 'verified'])
            ->prefix('friends')
            ->as('friends.')
            ->group(function () {
                Route::get('/', IndexController::class)->name('index');
            });

    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


// Маршруты API (чтобы работала аутентификация, они здесь)
Route::namespace('App\Http\Controllers\API')
    ->middleware('auth:sanctum')
    ->prefix('api')
    ->as('api.')
    ->group(function () {

        // Дружба
        Route::namespace('Friends')
            ->prefix('friends')
            ->as('friends.')
            ->group(function () {

                // Управление списком друзей
                Route::get('/', IndexController::class)->name('index');
                Route::delete('/{user}', DestroyController::class)->name('destroy');


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
                Route::post('/regenerate-friend-code', RegenerateFriendCodeController::class)->name('regenerate-friend-code');
            });

    });
