<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

Route::get('/', function () {
    return view('app', [
        'appName' => env('APP_NAME'),
        'shortUrlBase' => env('APP_URL') . env('SHORT_URL_PATH', '/')
    ]);
});

Route::get(env('SHORT_URL_PATH', '/') . '{alias}', [ShortUrlController::class, 'redirect']);
