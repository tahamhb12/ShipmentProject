<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Filament\Resources\ShipmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
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
                    Textarea::make('tracking_number')
                        ->label('Tracking Number')
                        ->required()
                        ->placeholder('Type Tracking Number...'),
                    Textarea::make('shipment_price')
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
                    "comment" => $data['comment'],
                ]);
            })
            ->form([
                Textarea::make('comment')
                    ->label('Comment')
                    ->required()
                    ->placeholder('Add a comment...')
            ])
            ->modalHeading('Reject Payout')
            ->modalSubmitActionLabel('Reject Payout'),
        ];
    }
}
