<?php

namespace App\Filament\Client\Resources\ShipmentResource\Pages;

use App\Filament\Client\Resources\ShipmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewShipment extends ViewRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->visible(fn($record) => $record->status === 'pending'),
            Action::make('downloadFiles')
            ->label('Download Files')
            ->icon('heroicon-o-arrow-down-tray')
            ->url(fn ($record) => route('shipments.download', $record))
        ];
    }
}
