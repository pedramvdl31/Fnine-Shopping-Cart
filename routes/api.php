<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// API route to get authenticated user data
Route::middleware('auth:sanctum')->get('/user', [HomeController::class, 'user']);
