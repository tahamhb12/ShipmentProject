<?php

namespace App\Filament\Exports;

use App\Models\Shipment;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ShipmentExporter extends Exporter
{
    protected static ?string $model = Shipment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user.name')->label('Sender'),
            ExportColumn::make('receiver.name')->label('Receiver'),
            ExportColumn::make('carrier.name')->label('Carrier'),
            ExportColumn::make('weight')->label('Weight'),
            ExportColumn::make('value')->label('Value'),
            ExportColumn::make('shipment_price')->label('Shipment Price')->default('none'),
            ExportColumn::make('state'),
            ExportColumn::make('city'),
            ExportColumn::make('country'),
            ExportColumn::make('postal_code'),
            ExportColumn::make('status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your shipment export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
