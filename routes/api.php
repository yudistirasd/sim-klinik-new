<?php

use App\Http\Controllers\Master\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['as' => 'api.'], function () {
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        Route::get('pengguna/dt', [UserController::class, 'dt'])->name('pengguna.dt');


        Route::apiResources([
            'pengguna' => UserController::class
        ], [
            'only' => ['store', 'edit', 'update', 'destroy']
        ]);
    });
});
