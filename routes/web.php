<?php

use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieController::class, 'home'])->name('home');

Route::controller(MovieController::class)->prefix('/movies')->name('movies.')->group(function () {
    Route::get('popular', 'getPopular')->name('popular');
    Route::get('top', 'getTopRated100')->name('top');
    Route::get('search', 'searchMovie')->name('search');
    Route::post('store', 'storeMovie')->name('store');
    Route::post('storeShow', 'storeShow')->name('storeShow');
    Route::get('{id}', 'show')->name('show');
});
