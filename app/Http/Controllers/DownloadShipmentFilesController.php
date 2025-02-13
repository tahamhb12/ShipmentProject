<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\Request;
use ZipArchive;

class DownloadShipmentFilesController extends Controller
{
    public function downloadShipmentFiles(Shipment $shipment)
    {
        $files = $shipment->attachment;
        if (!$files) {
            return back()->with('error', 'No files to download.');
        }

        $zipFile = storage_path("app/public/shipment_{$shipment->id}_files.zip");
        $zip = new ZipArchive;

        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Failed to create ZIP file.');
        }

        foreach ($files as $file) {
            $filePath = storage_path('app/public/shipment_files/' . basename($file));
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($file));
            }
        }

        $zip->close();

        return response()->download($zipFile)->deleteFileAfterSend(true);
    }
    public function downloadPaymentFiles(Payment $payment)
    {
        $files = $payment->attachment;
        if (!$files) {
            return back()->with('error', 'No files to download.');
        }

        $zipFile = storage_path("app/public/payment_{$payment->id}_files.zip");
        $zip = new ZipArchive;

        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Failed to create ZIP file.');
        }

        foreach ($files as $file) {
            $filePath = storage_path('app/public/payment_files/' . basename($file));
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($file));
            }
        }

        $zip->close();

        return response()->download($zipFile)->deleteFileAfterSend(true);
    }
}
