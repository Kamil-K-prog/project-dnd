<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::namespace('App\Http\Controllers\Web')->group(function () {

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
