<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
         *   records printing information and prints the invoice
* @param $id
*/
        public function printPurchaseInvoice ($id){

            // Retrieve shipment data
            $shipment_details = Shipment::findOrFail($id);
            //dd($shipment->tracking_number);
            $barcode = (new BarCodeGenerator())->generateBarCode($shipment_details->tracking_number);

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
            // now for pdf..
                $pdf = PDF::loadView( 'invoice',$pdf_infos);
                return $pdf->stream('document.pdf');
        }
}
