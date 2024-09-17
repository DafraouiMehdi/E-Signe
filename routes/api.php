<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MergeController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// In routes/api.php

// Route::post('/signPdf', [MergeController::class, 'signPdf'])->name('signPdf');


