<?php

use Illuminate\Support\Facades\Route;
use App\Models\Sale;

// Existing routes...

Route::get('/sales', function () {
    return Sale::all();
});
