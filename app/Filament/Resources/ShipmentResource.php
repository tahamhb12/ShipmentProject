<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ShipmentExporter;
use App\Filament\Resources\ShipmentResource\Pages;
use App\Filament\Resources\ShipmentResource\RelationManagers;
use App\Models\Carrier;
use App\Models\Shipment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use Parfaitementweb\FilamentCountryField\Infolists\Components\CountryEntry;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationGroup = 'Shipments';
    protected static ?int $navigationSort = -5;



    protected static ?string $navigationIcon = 'heroicon-s-truck';

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
                Select::make(name: 'user_id')->options(User::pluck('name','id'))->required()->searchable()->label('Sender')
                ->preload()
                ->createOptionForm([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->unique(User::class),
                    TextInput::make('password')->password(),
                    TextInput::make('password_confirmation')
                    ->password()
                    ->same('password'),
                    ])
                ->createOptionUsing(fn (array $data) => User::create($data)->id),
                Select::make(name: 'receiver_id')->options(User::pluck('name','id'))->required()->searchable()->label('Receiver')
                ->preload()
                ->createOptionForm([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->unique(User::class),
                    TextInput::make('password')->password(),
                    TextInput::make('password_confirmation')
                    ->password()
                    ->same('password'),
                    ])
                ->createOptionUsing(fn (array $data) => User::create($data)->id),
            ]),
            Section::make("Address")->schema([
                TextInput::make('street_address')->required(),
                TextInput::make('state')->required(),
                TextInput::make('city')->required(),
                Country::make('country')->required(),
                TextInput::make('postal_code')->required(),
            ])->collapsible()->columns(2),
                Section::make('Package Information')->schema([
                Select::make(name: 'carrier_id')->options(Carrier::pluck('name','id'))->required()->searchable()->label('Carrier')
                ->preload()
                ->createOptionForm([
                    TextInput::make('name')->required(),
                    TextInput::make('contact_email')->unique(Carrier::class),
                    FileUpload::make('logo')->required()->disk('public')->directory('images'),
                    ])
                ->createOptionUsing(fn (array $data) => Carrier::create($data)->id),
                TextInput::make("weight")->numeric()->required()->suffix('KG'),
                TextInput::make("value")->numeric()->required()->suffix('$'),
                Checkbox::make('isFlex')->label('Flex Shipment'),
                FileUpload::make("attachment")->disk('public')->directory('shipment_files')->multiple(),
                Textarea::make('description')->placeholder('about the shipment...')->required(),
                ])->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('receiver.name'),
                TextColumn::make('carrier.name'),
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
                    ->icon(fn($state)=>match ($state){
                        'approved'=>'heroicon-s-check-badge',
                        'rejected'=>'heroicon-s-x-circle',
                        'pending'=>'heroicon-s-arrow-path',
                    })
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
                SelectFilter::make('status')
                ->options(['Pending'=>'pending','Rejected'=>'rejected','Approved'=>'approved']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(ShipmentExporter::class)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist{
        return $infolist
        ->schema([
            Group::make()->schema([
                ComponentsSection::make('Recipient Details')->schema([
                    TextEntry::make('user.name')->label('Sender'),
                    TextEntry::make('receiver.name')->label('Receiver')
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
                    CountryEntry::make('country')
                        ->label('Country'),
                ])->collapsible(),
            ]),

            Group::make()->schema([
                ComponentsSection::make('Shipment Details')->schema([
                    TextEntry::make('tracking_number')
                    ->label('Tracking Number')
                    ->copyable()
                    ->default('Not Assigned Yet'),
                    TextEntry::make("status")
                    ->formatStateUsing(function($state){
                        if($state == "approved") return 'Approved';
                        if($state == "rejected") return 'Rejected';
                        if($state == "pending") return 'Pending';
                    })
                    ->badge()
                    ->icon(fn($state)=>match ($state){
                        'approved'=>'heroicon-s-check-badge',
                        'rejected'=>'heroicon-s-x-circle',
                        'pending'=>'heroicon-s-arrow-path',
                    })
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        "approved" => 'success',
                        "rejected" => 'danger',
                    }),
                    TextEntry::make('carrier.name')
                        ->label('Carrier'),
                    TextEntry::make('shipment_price')
                        ->label('Shipment Price')
                        ->default('Not Assigned Yet')
                        ->money('mad'),
                    TextEntry::make('value')
                        ->label('Value')
                        ->money('mad'),
                    TextEntry::make('weight')
                        ->label('Weight (kg)')
                        ->suffix('kg'),
                    TextEntry::make('isFlex')
                        ->label('Flexible Shipping')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'danger')
                        ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    TextEntry::make('reason')
                        ->label('Reason')
                        ->hidden(fn ($record) => empty($record->reason)),
                    TextEntry::make('description')->copyable()->limit(25)->default('No description'),
                ])->collapsible()->columns(2),
                ComponentsSection::make('Files')->schema([
                    ImageEntry::make('attachment')
                        ->label('Attachment')
                        ->size(100),
                ])->collapsible(),
            ])->columnSpan(2)

        ])->columns(3);
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
