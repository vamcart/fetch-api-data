<?php

use Illuminate\Support\Facades\Route;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Order;

// Existing routes...

Route::get('/sales', function () {
    return Sale::all();
});

Route::get('/orders', function () {
    return Order::all();
});

Route::get('/stocks', function () {
    $stocks = Stock::all();
    return response()->json($stocks);
});

