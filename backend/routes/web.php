<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

route::get('/{any}', function () {
    return redirect('http://localhost:5173');
})->where('any', '.*');
