<?php

namespace App\Filament\Client\Resources\ApprovedShipmentResource\Pages;

use App\Filament\Client\Resources\ApprovedShipmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewApprovedShipment extends ViewRecord
{
    protected static string $resource = ApprovedShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Action::make('downloadFiles')
            ->label('Download Files')
            ->icon('heroicon-o-arrow-down-tray')
            ->url(fn ($record) => route('shipments.download', $record))
        ];
    }
}
