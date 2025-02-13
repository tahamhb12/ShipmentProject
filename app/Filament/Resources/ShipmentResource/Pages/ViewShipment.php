<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Filament\Resources\ShipmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;

class ViewShipment extends ViewRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Action::make("Accept")
                ->color('success')
                ->visible(fn($record) => $record->status === "pending")
                ->action(function ($record, array $data) {
                    $record->update([
                        "status" => "approved",
                        "tracking_number" => $data['tracking_number'],
                        "shipment_price" => $data['shipment_price'],
                    ]);
                })
                ->form([
                    TextInput::make('tracking_number')
                        ->label('Tracking Number')
                        ->required()
                        ->placeholder('Type Tracking Number...'),
                    TextInput::make('shipment_price')
                        ->label('Shipment Price')
                        ->required()
                        ->placeholder('Type Shipment Price...'),
                ])
                ->modalHeading('Accept Shipment Request')
                ->modalSubmitActionLabel('Approve Shipment Request'),

            Action::make("Reject")
                ->color('danger')
                ->visible(fn($record) => $record->status === "pending")
                ->action(function ($record, array $data) {
                    $record->update([
                        "status" => "rejected",
                        "reason" => $data['reason'],
                    ]);
                })
                ->form([
                    TextInput::make('reason')
                        ->label('Reason')
                        ->required()
                        ->placeholder('Reason...')
                ])
                ->modalHeading('Reject Shipment Request')
                ->modalSubmitActionLabel('Reject Shipment Request'),
                Action::make('downloadFiles')
                ->label('Download Files')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn ($record) => route('shipments.download', $record))
            ];
    }
}
