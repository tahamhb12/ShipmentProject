<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentResource\Pages;
use App\Filament\Resources\ShipmentResource\RelationManagers;
use App\Models\Carrier;
use App\Models\Shipment;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make(name: 'carrier_id')->options(Carrier::pluck('name','id'))->required(),
                    TextInput::make("to_address")->label('Receiver Address'),
                    TextInput::make("weight")->numeric(),
                    TextInput::make("value")->numeric(),
                    TextInput::make("tracking_number"),
                    TextInput::make("shipment_price")->numeric(),
                    FileUpload::make("attachment")->disk('public')->directory('shipment_images'),
                    Checkbox::make('isFlex')->label('Flex Shipment'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('carrier.name'),
                TextColumn::make('to_address'),
                TextColumn::make('weight')->formatStateUsing(fn($state)=>$state.'Kg'),
                TextColumn::make('value')->money('usd'),
                TextColumn::make('shipment_price')->money('usd'),
                ImageColumn::make('attachment'),
                TextColumn::make('isFlex')->label('Flex Shipment')->formatStateUsing(fn($state)=>$state == 1 ? "Yes" : "No"),
            ])
            ->filters([
                //
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
