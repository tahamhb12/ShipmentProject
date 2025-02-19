<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class BarCodeGenerator extends Controller
{
    public function generateBarCode($id){
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($id, $generator::TYPE_CODE_128, 5, 120);

        return $barcode;
    }

    public function generatePDF($id)
    {
        $shipment_details = Shipment::find($id);
        $barcode = $this->generateBarCode($shipment_details['tracking_number']);

        $pdf_infos = [
            'sender'=>$shipment_details->user->name,
            'receiver'=>$shipment_details->receiver->name,
            'street_address'=>$shipment_details->street_address,
            'city'=>$shipment_details->city,
            'postal_code'=>$shipment_details->postal_code,
            'state'=>$shipment_details->state,
            'country'=>$shipment_details->country,
            'tracking_number'=>$shipment_details->tracking_number,
            'weight'=>$shipment_details->weight,
            'barcode' =>$barcode
        ];
        $pdf = Pdf::loadView('invoice.invoice', $pdf_infos);
        return $pdf->stream('document.pdf');
    }

}
