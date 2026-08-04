<?php

use App\Contexts\GestionEspacios\Infrastructure\Http\Controllers\EquipamientoController;
use App\Contexts\GestionEspacios\Infrastructure\Http\Controllers\EspacioController;
use App\Contexts\GestionEspacios\Infrastructure\Http\Controllers\RecintoController;
use App\Contexts\GestionEspacios\Infrastructure\Http\Controllers\ReservaPuntualController;
use Illuminate\Support\Facades\Route;

Route::apiResource('espacios', EspacioController::class);
Route::post('espacios/{id}/equipamientos', [EspacioController::class, 'asignarEquipamiento']);
Route::delete('espacios/{id}/equipamientos/{equipamientoId}', [EspacioController::class, 'quitarEquipamiento']);

Route::apiResource('recintos', RecintoController::class);

Route::apiResource('equipamientos', EquipamientoController::class);

Route::apiResource('reservas', ReservaPuntualController::class);
Route::post('reservas/{id}/aprobar', [ReservaPuntualController::class, 'aprobar']);
Route::post('reservas/{id}/rechazar', [ReservaPuntualController::class, 'rechazar']);
Route::post('reservas/{id}/cancelar', [ReservaPuntualController::class, 'cancelar']);
