<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;

class BarCodeGenerator extends Controller
{
    public function generateBarCode($id){
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($id, $generator::TYPE_CODE_128, 2, 70, );

        return $barcode;
    }
}
