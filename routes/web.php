<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CertPrivateKeyController;
use App\Http\Controllers\MergeController;


Route::get('/', function () {
    return view('welcome');
});


// listCertificates worked
Route::get('/certificates', [CertificateController::class, 'listCertificates']);





// is worked
Route::get('/getcertificate', [PdfController::class, 'getCertificates']);





// Route::post('/certify', [PdfController::class, 'certifyPdf']);
// Route::post('/certify', [CertPrivateKeyController::class, 'certify']);

// Merge pdf with certificate
Route::post('signPdf', [MergeController::class, 'signPdf'])->name('signPdf');
// Route::post('/signPdf', [MergeController::class, 'signPdf'])->name('mergePdf');




