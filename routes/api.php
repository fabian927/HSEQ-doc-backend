<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RollController;
use App\Http\Controllers\UserController;

//Manejo de rutas API para el Controller Personas

Route::get('/persons', [PersonsController::class, 'getPersons']);
Route::get('/persons/{document}', [PersonsController::class, 'getById']);
Route::post('/persons', [PersonsController::class, 'createPerson']);
Route::patch('/persons/{id}', [PersonsController::class, 'updatePerson']);
Route::delete('/persons{id}', [PersonsController::class, 'deletPerson']);

//Manejo de rutas API para el Controller Location

Route::get('/location', [LocationController::class, 'getLocation']);
Route::get('/location{id}', [LocationController::class, 'getById']);
Route::post('/location', [LocationController::class, 'createLocation']);
Route::put('/location{id}', [LocationController::class, 'updateLocation']);
Route::delete('/location{id}', [LocationController::class, 'deleteLocation']);

//Manejo de rutas API para el Controller User

Route::post('/login', [UserController::class, 'login']);
Route::get('/user', [UserController::class, 'getUsers']);
Route::post('/user', [UserController::class, 'createUser']);

//Manejo de rutas API para el Controller Roll

Route::get('/roll', [RollController::class, 'getRoll']);
Route::get('/roll{id}', [RollController::class, 'getById']);
Route::post('/roll', [RollController::class, 'createRoll']);
Route::patch('/roll{id}', [RollController::class, 'updateRoll']);
Route::delete('/roll{id}', [RollController::class, 'deleteRoll']);
