<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

// Temporary Preview Routes for Auth Pages
Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});
