<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\ShipmentResource\Pages;
use App\Filament\Client\Resources\ShipmentResource\RelationManagers;
use App\Filament\Exports\ShipmentExporter;
use App\Models\Carrier;
use App\Models\Shipment;
use App\Models\User;
use Dompdf\FrameDecorator\Text;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
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
use ZipArchive;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-s-truck';
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
                Country::make('country')->required(),
                TextInput::make('postal_code')->required(),
            ])->collapsible()->columns(2),
            Section::make('Package Information')->schema([
                Select::make(name: 'carrier_id')->options(Carrier::pluck('name','id'))->required()->searchable()->label('Carrier'),
                TextInput::make("weight")->numeric()->required()->suffix('KG'),
                TextInput::make("value")->numeric()->required()->suffix('$'),
                Checkbox::make('isFlex')->label('Flex Shipment'),
                FileUpload::make("attachment")->disk('public')->directory('shipment_files')->multiple(),
                Textarea::make('description')->required()->placeholder('about the shipment...'),
                ])->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receiver.name')->searchable(),
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
                }),
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
                Tables\Actions\EditAction::make()
                ->visible(fn($record) => $record->status === 'pending'),
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
                    CountryEntry::make('country')
                        ->label('Country'),
                ])->collapsible(),
            ]),

            Group::make()->schema([
                ComponentsSection::make('Shipment Details')->schema([
                    TextEntry::make('tracking_number')
                    ->label('Tracking Number')
                    ->copyable()
                    ->hidden(fn($record)=>$record->status !=='approved'),
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
                    TextEntry::make('reason')
                        ->label('Reason')
                        ->hidden(fn ($record) => empty($record->reason)),
                    TextEntry::make('shipment_price')
                        ->label('Shipment Price')
                        ->money('mad')
                        ->hidden(fn($record)=>$record->status !=='approved'),
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
