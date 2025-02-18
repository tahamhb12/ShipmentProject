<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;

class BarCodeGenerator extends Controller
{
    public function generateBarCode($id){
        $barcode = (new \Picqer\Barcode\Types\TypeCode128())->getBarcode($id);

        $renderer = new \Picqer\Barcode\Renderers\HtmlRenderer();
        return $renderer->render($barcode);
    }
}
