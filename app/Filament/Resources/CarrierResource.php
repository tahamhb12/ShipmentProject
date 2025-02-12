<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarrierResource\Pages;
use App\Filament\Resources\CarrierResource\RelationManagers;
use App\Models\Carrier;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarrierResource extends Resource
{
    protected static ?string $model = Carrier::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-office-2';
    protected static ?string $navigationGroup = 'Shipments';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('contact_email')->required(),
                FileUpload::make('logo')->required()->disk('public')->directory('images'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo'),
                TextColumn::make('name'),
                TextColumn::make('contact_email'),
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
            'index' => Pages\ListCarriers::route('/'),
            'create' => Pages\CreateCarrier::route('/create'),
            'view' => Pages\ViewCarrier::route('/{record}'),
            'edit' => Pages\EditCarrier::route('/{record}/edit'),
        ];
    }
}
