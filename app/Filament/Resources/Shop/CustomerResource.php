<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\CustomerResource\Pages;
use App\Filament\Resources\Shop\CustomerResource\RelationManagers;
use App\Models\City;
use App\Models\Shop\Customer;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $slug = 'customers';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema(
                        [
                            Forms\Components\Select::make('country_id')
                                ->label('Country')
                                ->native(false)
                                ->relationship('country', 'name')
                                ->preload()
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(
                                    function (Set $set) {
                                        $set('state_id', null);
                                        $set('city_id', null);
                                    }
                                ),

                            Forms\Components\Select::make('state_id')
                                ->label('State')
                                ->native(false)
                                ->options(
                                    fn(Get $get) => State::where('country_id', $get('country_id'))
                                        ->pluck('name', 'id')
                                )
                                ->preload()
                                ->live()
                                ->searchable()
                                ->afterStateUpdated(
                                    fn(Set $set) => $set('city_id', null)
                                ),

                            Forms\Components\Select::make('city_id')
                                ->label('City')
                                ->native(false)
                                ->options(
                                    fn(Get $get) => City::where('state_id', $get('state_id'))
                                        ->pluck('name', 'id')
                                )
                                ->preload()
                                ->live()
                                ->searchable()
                        ]
                    )->columns(3),

                Forms\Components\Section::make()
                    ->schema(
                        [
                            Forms\Components\TextInput::make('name')
                                ->required(),
                            Forms\Components\TextInput::make('email')
                                ->label('Email Address')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]
                    )->columns(2),
                Forms\Components\Section::make()
                    ->schema(
                        [
                            Forms\Components\TextInput::make('phone')
                                ->label('Phone Number')
                                ->numeric(),
                            Forms\Components\DatePicker::make('birthday')
                                ->native(false)
                                ->label('Birthday'),
                            Forms\Components\Select::make('gender')
                                ->native(false)
                                ->label('Gender')
                                ->options(
                                    [
                                        'Male' => 'Male',
                                        'Female' => 'Female',
                                    ]
                                ),

                        ]
                    )->columns(3),

                Forms\Components\FileUpload::make('photo')
                    ->label('Image')
                    ->directory('customer-images')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable(isIndividual: true),

                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country'),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('State'),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('City'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone Number'),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Birthday')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
