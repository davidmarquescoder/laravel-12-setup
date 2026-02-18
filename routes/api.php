<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health-check', function (Request $request) {
    return response()->json([
        'status'  => 'success',
        'message' => 'API is running',
    ]);
});
