<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\AddressResource\Pages;
use App\Filament\Client\Resources\AddressResource\RelationManagers;
use App\Models\Address;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-s-map-pin';
    protected static ?string $navigationGroup = 'Shipments';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('street_address')->required(),
                TextInput::make('city')->required(),
                TextInput::make('state')->required(),
                TextInput::make('postal_code')->required(),
                Country::make('country')->required(),
            ]);
   }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('street_address')->searchable(),
                TextColumn::make('city')->searchable(),
                TextColumn::make('state')->searchable(),
                TextColumn::make('postal_code')->searchable(),
                TextColumn::make('country')->searchable(),
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
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'view' => Pages\ViewAddress::route('/{record}'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
