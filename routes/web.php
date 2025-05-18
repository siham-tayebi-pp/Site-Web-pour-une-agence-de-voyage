<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('home');
});

Route::get('/voyages', function () {
    return view('voyages.index');
});

Route::get('/voyages/{id}', function ($id) {
    return view('voyages.show', ['id' => $id]);
});

Route::get('/reservation/{id}', function ($id) {
    return view('reservations.create', ['id' => $id]);
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/profile', function () {
    return view('user.profile');
});

Route::get('/messages', function () {
    return view('messages.index');
});

Route::get('/notifications', function () {
    return view('notifications.index');
});

Route::get('/contact', function () {
    return view('contact.index');
});