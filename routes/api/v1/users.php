<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::name('users.')
    // ->namespace('App')
    ->group(
        function () {
            Route::get(
                '/users',
                [UserController::class, 'index']
            )
                ->name('index')
                ->withoutMiddleware('auth');

            Route::get(
                '/users/{user}',
                [App\Http\Controllers\UserController::class, 'show']
            )
                ->name('show')
                ->whereNumber('user');

            Route::post(
                '/users',
                [App\Http\Controllers\UserController::class, 'store']
            )->name('store');

            Route::put(
                '/users/{user}',
                [UserController::class, 'update']
            )
                ->name('update')
                ->whereNumber('user');

            Route::delete(
                '/users/{user}',
                [UserController::class, 'destroy']
            )
                ->name('destroy');
        }
    );
