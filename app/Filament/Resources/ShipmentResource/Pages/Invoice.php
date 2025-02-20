<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Filament\Resources\ShipmentResource;
use App\Http\Controllers\BarCodeGenerator;
use App\Models\Shipment;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class Invoice extends Page
{
    protected static string $resource = ShipmentResource::class;
    public $record;
    public $shipment;
    public $codebar;

    public function mount ($record){
        $this->record = $record;
        $this->shipment = Shipment::find($this->record);
        $bar = new BarCodeGenerator();
        $this->codebar = $bar->generateBarCode($this->shipment->tracking_number);
    }
    public function getHeaderActions(): array{
        return [
            Action::make('print_invoice')
            ->label('Print Invoice')
            ->icon('heroicon-o-printer')
            ->color('primary')
            ->url(route('download.invoice',['id'=>$this->record] ))
    ];
    }



    protected static string $view = 'filament.resources.shipment-resource.pages.invoive';
}



//
