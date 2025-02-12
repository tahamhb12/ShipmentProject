<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DownloadShipmentFilesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/shipments/{shipment}/download', [DownloadShipmentFilesController::class, 'downloadFiles'])
    ->name('shipments.download');
