<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\ShipmentResource\Pages;
use App\Filament\Client\Resources\ShipmentResource\RelationManagers;
use App\Models\Carrier;
use App\Models\Shipment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Shipments';
    protected static ?int $navigationSort = -3;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Section::make()->schema([
                TextInput::make("receiver")->label('Receiver Name')->required()->visibleOn('create'),
                TextInput::make("receiver_id")->label('Receiver Name')->formatStateUsing(function($state){
                    $user = User::find($state);
                    return $user?->name;
                })
                ->disabled()
                ->dehydrated(false)
                ->visible(fn ($record) => $record !== null),
            ]),
            Section::make("Address")->schema([
                TextInput::make('street_address')->required(),
                TextInput::make('state')->required(),
                TextInput::make('city')->required(),
                TextInput::make('country')->required(),
                TextInput::make('postal_code')->required(),
            ])->collapsible()->columns(2),
            Section::make('Package Information')->schema([
                Select::make(name: 'carrier_id')->options(Carrier::pluck('name','id'))->required()->searchable()->label('Carrier'),
                TextInput::make("weight")->numeric()->required()->suffix('KG'),
                TextInput::make("value")->numeric()->required()->suffix('$'),
                FileUpload::make("attachment")->disk('public')->directory('shipment_images')->required()->multiple(),
                Checkbox::make('isFlex')->label('Flex Shipment'),
            ])->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receiver.name'),
                TextColumn::make('carrier.name'),
                TextColumn::make('weight')->formatStateUsing(fn($state)=>$state.'Kg'),
                TextColumn::make('value')->money('mad'),
                TextColumn::make('shipment_price')->money('mad')->default('Not Assigned Yet'),
                ImageColumn::make('attachment'),
                IconColumn::make('isFlex')->label('Flex Shipment')->boolean(),
                TextColumn::make("status")
                ->formatStateUsing(function($state){
                    if($state == "approved") return 'Approved';
                    if($state == "rejected") return 'Rejected';
                    if($state == "pending") return 'Pending';
                })
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'pending' => 'warning',
                    "approved" => 'success',
                    "rejected" => 'danger',
                }),
                TextColumn::make('reason')->default('No Reason'),
            ])
            ->filters([
                SelectFilter::make("status")->options([
                    "pending" => "pending",
                    "approved" => "approved",
                    "rejected" => "rejected",
                ])->multiple()
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'view' => Pages\ViewShipment::route('/{record}'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}
