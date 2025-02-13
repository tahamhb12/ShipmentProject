<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\ApprovedShipmentResource\Pages;
use App\Filament\Resources\ApprovedShipmentResource\RelationManagers;
use App\Models\ApprovedShipment;
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
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class ApprovedShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Approved';
    protected static ?string $pluralLabel = 'Approved';

    protected static ?string $navigationParentItem = 'Shipments';
    protected static ?string $navigationGroup = 'Shipments';




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
            ->query(Shipment::query()->where('status','approved'))
            ->columns([
                TextColumn::make('receiver.name')->searchable(),
                TextColumn::make('carrier.name')->searchable(),
                TextColumn::make('weight')->formatStateUsing(fn($state)=>$state.' Kg'),
                TextColumn::make('value')->money('mad'),
                TextColumn::make('shipment_price')->money('mad')->default('Not Assigned Yet')->label('Price'),
                IconColumn::make('isFlex')->label('Flex')->boolean(),
                TextColumn::make("status")
                ->formatStateUsing(function($state){
                    if($state == "approved") return 'Approved';
                    if($state == "rejected") return 'Rejected';
                    if($state == "pending") return 'Pending';
                    })
                    ->badge()
                    ->icon('heroicon-s-check-badge')
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        "approved" => 'success',
                        "rejected" => 'danger',
                    })
            ])
            ->filters([
                SelectFilter::make('carrier')
                ->relationship('carrier','name'),
                SelectFilter::make('isFlex')
                ->options([true=>'Yes',false=>'No'])->label('Flex?'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist{
        return $infolist
        ->schema([
            Group::make()->schema([
                ComponentsSection::make('Recipient Details')->schema([
                    TextEntry::make('receiver.name')->label('Name')
                ])->collapsible(),
                ComponentsSection::make('Address')->schema([
                    TextEntry::make('street_address')
                    ->label('Street Address')
                    ->columnSpanFull(),
                    TextEntry::make('city')
                        ->label('City'),
                    TextEntry::make('state')
                        ->label('State'),
                    TextEntry::make('postal_code')
                        ->label('Postal Code'),
                    TextEntry::make('country')
                        ->label('Country'),
                ])->collapsible(),
            ]),

            Group::make()->schema([
                ComponentsSection::make('Shipment Details')->schema([
                    TextEntry::make('tracking_number')
                    ->label('Tracking Number')
                    ->copyable(),
                    TextEntry::make("status")
                    ->formatStateUsing(function($state){
                        if($state == "approved") return 'Approved';
                        if($state == "rejected") return 'Rejected';
                        if($state == "pending") return 'Pending';
                        })
                        ->badge()
                        ->icon('heroicon-s-check-badge')
                        ->color(fn ($state) => match ($state) {
                            'pending' => 'warning',
                            "approved" => 'success',
                            "rejected" => 'danger',
                        }),
                        TextEntry::make('shipment_price')
                        ->label('Shipment Price')
                        ->money('mad'),
                        TextEntry::make('value')
                        ->label('Value')
                        ->money('mad'),
                        TextEntry::make('weight')
                        ->label('Weight (kg)')
                        ->suffix(' kg'),
                        TextEntry::make('carrier.name')
                            ->label('Carrier'),
                        TextEntry::make('isFlex')
                        ->label('Flexible')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'danger')
                        ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    TextEntry::make('reason')
                        ->label('Reason')
                        ->hidden(fn ($record) => empty($record->reason)),
                ])->collapsible()->columns(2),
                ComponentsSection::make('Shipment Details')->schema([
                    ImageEntry::make('attachment')
                        ->label('Attachment')
                        ->size(200),
                ])->collapsible(),
            ])->columnSpan(2)

        ])->columns(3);
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $model): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovedShipments::route('/'),
            'create' => Pages\CreateApprovedShipment::route('/create'),
            'view' => Pages\ViewApprovedShipment::route('/{record}'),
            'edit' => Pages\EditApprovedShipment::route('/{record}/edit'),
        ];
    }
}
