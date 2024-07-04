<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tinker', function () {
    return view('tinker')
        ->with('command', '');
});

Route::post('/tinker', function () {
    return view('tinker')
        ->with('command', request()->get('command'));
});
