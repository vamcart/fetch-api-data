<?php

use Illuminate\Support\Facades\Route;
use App\Models\Joke;
use App\Models\Stock;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stocks', function () {
    $stocks = Stock::all();
    return response()->json($stocks);
});
