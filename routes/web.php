<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;


Route::get('/', [ClienteController::class, 'landing'])->name('landing');


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/store', [ClienteController::class, 'store'])->name('registrar');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');





Route::middleware(['auth.custom'])->group(function () {
    Route::get('/home', [ClienteController::class, 'index'])->name('home');
    Route::get('/home/export', [ClienteController::class, 'export'])->name('home.export');
    Route::get('/home/seleccionar-ganador', [ClienteController::class, 'seleccionarGanador'])->name('home.seleccionarGanador');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


