<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

Route::get('/', function () {
    return view('app', [
        'shortUrlBase' => env('APP_URL') . env('SHORT_URL_PATH', '/')
    ]);
});

Route::get('/encode', [ShortUrlController::class, 'encode']);
Route::get('/decode', [ShortUrlController::class, 'decode']);
Route::get(env('SHORT_URL_PATH', '/') . '{alias}', [ShortUrlController::class, 'redirect']);

Route::get('/links', [ShortUrlController::class, 'list']);
