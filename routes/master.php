<?php

use App\Http\Controllers\Master\UserController;

Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
    Route::get('pengguna', [UserController::class, 'index'])->name('pengguna.index');
});
