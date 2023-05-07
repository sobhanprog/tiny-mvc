<?php

use System\Router\Web\Route;



Route::get('/', [App\Http\Controllers\HomeController::class, 'index'], 'home');
Route::get('create', [App\Http\Controllers\HomeController::class, 'create'], 'create');
Route::post('/store', [App\Http\Controllers\HomeController::class, 'store'], 'store');
Route::get('edit/{id}', [App\Http\Controllers\HomeController::class, 'edit'], 'edit');
Route::put('/update/{id}', [App\Http\Controllers\HomeController::class, 'update'], 'update');
Route::delete('/delete/{id}', [App\Http\Controllers\HomeController::class, 'delete'], 'delete');



Route::get('/show/{id}', [HomeController::class, 'show'], 'show');
Route::get('/create', [HomeController::class, 'create'], 'create');

    




