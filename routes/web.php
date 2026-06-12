<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HMTController;

Route::prefix('HMT')->group(function () {
    Route::get('/', [HMTController::class, 'home'])->name('hmt.home');
    Route::get('/quesandans', [HMTController::class, 'quesAndAns'])->name('hmt.quesandans');
    Route::post('/answered', [HMTController::class, 'markAnswered'])->name('hmt.markAnswered');
    Route::post('/reset', [HMTController::class, 'resetAnswered'])->name('hmt.resetAnswered');

});
