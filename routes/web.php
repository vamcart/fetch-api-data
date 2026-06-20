<?php

use Illuminate\Support\Facades\Route;
use App\Models\{Sale, Stock, Order, Income};

$resources = [
    'sales' => Sale::class,
    'orders' => Order::class,
    'stocks' => Stock::class,
    'incomes' => Income::class,
];
foreach ($resources as $uri => $model) {
    Route::get("/$uri", function () use ($model) {
        return response()->json($model::all());
    });
}
