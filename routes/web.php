<?php

use App\Http\Controllers\BarCodeGenerator as ControllersBarCodeGenerator;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DownloadShipmentFilesController;
use Illuminate\Support\Facades\Route;
use Picqer\Barcode\BarcodeGenerator;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/shipments/{shipment}/download', [DownloadShipmentFilesController::class, 'downloadShipmentFiles'])
    ->name('shipments.download');
Route::get('/payments/{payment}/download', [DownloadShipmentFilesController::class, 'downloadPaymentFiles'])
    ->name('payments.download');

Route::get('/barcode/{id}',[ControllersBarCodeGenerator::class,'generateBarCode'])->name('barcode.generate');
