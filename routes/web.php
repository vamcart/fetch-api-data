<?php

use Illuminate\Support\Facades\Route;
use App\Models\{Sale, Stock, Order, Income};

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

Route::get('/incomes', function () {
    $incomes = Income::all();
    return response()->json($incomes);
});
