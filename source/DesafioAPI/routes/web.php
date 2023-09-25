<?php

use App\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('registros', [RegistroController::class, 'index']);

Route::get('registros/{id}', [RegistroController::class, 'show']);

Route::post('registros', [RegistroController::class, 'store']);

Route::put('registros/{id}', [RegistroController::class, 'update']);

Route::delete('registros/{id}', [RegistroController::class, 'destroy']);
