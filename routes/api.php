<?php

use App\Helpers\Routes\RouteHelper;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::prefix('v1')
    
    ->group(
        function () {
            RouteHelper::includeRouteFiles(__DIR__ . '/api/v1');


        }
    );
