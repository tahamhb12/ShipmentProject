<?php

namespace App\Filament\Manager\Resources;

use App\Filament\Manager\Resources\PendingShipmentResource\Pages;
use App\Filament\Resources\PendingShipmentResource\RelationManagers;
use App\Models\Carrier;
use App\Models\PendingShipment;
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
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class PendingShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;
    protected static ?string $label = 'Pending Shipments';
    protected static ?string $navigationParentItem = 'Shipments';
    protected static ?string $navigationGroup = 'Shipments';
    protected static ?int $navigationSort = -2;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make("tracking_number")
                    ->visible(fn ($record) => $record !== null),
                    TextInput::make("shipment_price")
                    ->visible(fn ($record) => $record !== null)
                    ->suffix('$'),
                    Select::make(name: 'user_id')->options(User::pluck('name','id'))->required()->searchable()->label('Sender'),
                    Select::make(name: 'receiver_id')->options(User::pluck('name','id'))->required()->searchable()->label('Receiver'),
                ]),
                Section::make("Address")->schema([
                    TextInput::make('street_address')->required(),
                    TextInput::make('state')->required(),
                    TextInput::make('city')->required(),
                    Country::make('country')->required(),
                    TextInput::make('postal_code')->required(),
                ])->collapsible()->columns(2),
                Section::make('Package Information')->schema([
                    Select::make(name: 'carrier_id')->options(Carrier::pluck('name','id'))->required()->searchable()->label('Carrier'),
                    TextInput::make("weight")->numeric()->required()->suffix('KG'),
                    TextInput::make("value")->numeric()->required()->suffix('$'),
                    FileUpload::make("attachment")->disk('public')->directory('shipment_files')->required()->multiple(),
                    Checkbox::make('isFlex')->label('Flex Shipment'),
                ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Shipment::query()->where('status','pending'))
        ->columns([
            TextColumn::make('user.name'),
            TextColumn::make('receiver.name'),
            TextColumn::make('carrier.name'),
            TextColumn::make('weight')->formatStateUsing(fn($state)=>$state.' Kg'),
            TextColumn::make('value')->money('mad'),
            IconColumn::make('isFlex')->label('Flex Shipment')->boolean(),
            TextColumn::make("status")
            ->formatStateUsing(function($state){
                if($state == "approved") return 'Approved';
                if($state == "rejected") return 'Rejected';
                if($state == "pending") return 'Pending';
                })
                ->badge()
                ->icon('heroicon-s-arrow-path')
                ->color(fn ($state) => match ($state) {
                    'pending' => 'warning',
                    "approved" => 'success',
                    "rejected" => 'danger',
                })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('downloadFiles')
                ->label('Download Files')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn ($record) => route('shipments.download', $record))
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
            'index' => Pages\ListPendingShipments::route('/'),
            'create' => Pages\CreatePendingShipment::route('/create'),
            'view' => Pages\ViewPendingShipment::route('/{record}'),
            'edit' => Pages\EditPendingShipment::route('/{record}/edit'),
        ];
    }
}
