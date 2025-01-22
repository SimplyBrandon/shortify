<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

Route::get('/encode', [ShortUrlController::class, 'encode']);
Route::get('/decode', [ShortUrlController::class, 'decode']);

Route::get('/links', [ShortUrlController::class, 'list']);
