<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PexelsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/pexels-veri-guncelle', [PexelsController::class, 'pexelsUpdateFront'])->name('pexels-guncelle');
Route::post('/pexels-veri-guncelle', [PexelsController::class, 'pexelsUpdatePost'])->name('pexels-guncelle-post');
Route::get('/reset-site', [PexelsController::class, 'pexelsReset'])->name('pexels-reset');
Route::get('/', [PexelsController::class, 'pexelsIndexFront'])->name('pexels-home');
Route::get('/photographer/{id}', [PexelsController::class, 'pexelsPhotographerFront'])->name('pexels-photographer-single');

