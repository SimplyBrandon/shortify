<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

Route::get('/', function () {
    return view('app', [
        'appName' => env('APP_NAME'),
        'shortUrlBase' => env('APP_URL') . env('SHORT_URL_PATH', '/')
    ]);
});

Route::get('/encode', [ShortUrlController::class, 'encode']);
Route::get('/decode', [ShortUrlController::class, 'decode']);

Route::get('/links', [ShortUrlController::class, 'list']);

Route::get(env('SHORT_URL_PATH', '/') . '{alias}', [ShortUrlController::class, 'redirect']);
