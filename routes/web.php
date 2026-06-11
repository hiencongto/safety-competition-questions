<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HMTController;

Route::prefix('HMT')->group(function () {
    Route::get('/home', [HMTController::class, 'home'])->name('hmt.home');
    Route::get('/quesandans', [HMTController::class, 'quesAndAns'])->name('hmt.quesandans');

});
