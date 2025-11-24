<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/heath-check', function (Request $request) {
    return response()->json([
        'status'  => 'success',
        'message' => 'API is running',
    ]);
});
