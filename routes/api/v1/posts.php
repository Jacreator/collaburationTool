<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::middleware('auth')
    ->name('posts.')
    ->group(
        function () {
            Route::get('/posts', [PostController::class, 'index'])
                ->name('index');

            Route::get('/posts/{post}', [PostController::class, 'show'])
                ->name('show')
                ->whereNumber('post');

            Route::post('/posts', [PostController::class, 'store'])
                ->name('store');

            Route::put('/posts/{post}', [PostController::class, 'update'])
                ->name('update')
                ->whereNumber('post');

            Route::delete('/posts/{post}', [PostController::class, 'destroy'])
                ->name('destroy');
        }
    );
