<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\OrderResource\Pages;
use App\Filament\Resources\Shop\OrderResource\RelationManagers;
use App\Models\Shop\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use App\Enums\OrderStatus;
use App\Enums\OrderPayment;
use App\Models\Shop\Product;
use Filament\Forms\Set;
use Filament\Forms\Get;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $slug = 'orders';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_order', 'New')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'primary' : 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Order Details')
                        ->icon('heroicon-m-adjustments-horizontal')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema(static::getDetails()),

                    Wizard\Step::make('Order Items')
                        ->icon('heroicon-m-shopping-bag')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema(static::getItems()),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer'),

                Tables\Columns\TextColumn::make('status_order')
                    ->label('Order Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'New' => 'info',
                        'Processing' => 'primary',
                        'Shipped' => 'success',
                        'Delivered' => 'success',
                        'Cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency'),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 2, '.', '.'))
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money(),
                    ]),

                Tables\Columns\TextColumn::make('status_payment')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Unpaid' => 'danger'
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getDetails(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema(
                    [
                        Forms\Components\TextInput::make('number')
                            ->default('D2-' . random_int(100000, 999999))
                            ->readonly()
                            ->required()
                            ->maxLength(32)
                            ->unique(Order::class, 'number', ignoreRecord: true),

                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->native(false)
                            ->required()
                            ->relationship('customer', 'name')
                            ->preload()
                            ->searchable(),
                    ]
                )->columns(2),

            Forms\Components\Section::make()
                ->schema(
                    [
                        Forms\Components\TextInput::make('currency')
                            ->label('Currency')
                            ->readOnly()
                            ->default('VND'),

                        Forms\Components\DatePicker::make('order_date')
                            ->label('Order Date')
                            ->default(now())
                    ]
                )->columns(2),

            Forms\Components\Section::make()
                ->schema(
                    [
                        Forms\Components\ToggleButtons::make('status_order')
                            ->label('Order Status')
                            ->required()
                            ->options(OrderStatus::class)
                            ->inline(),

                        Forms\Components\ToggleButtons::make('status_payment')
                            ->label('Payment Status')
                            ->required()
                            ->options(OrderPayment::class)
                            ->inline()
                    ]
                )->columns(2),

            Forms\Components\MarkdownEditor::make('notes')
                ->label('Notes')
                ->columnSpanFull()
        ];
    }

    public static function getItems()
    {
        return [
            Forms\Components\Repeater::make('items')
                ->schema(
                    [
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->native(false)
                            ->required()
                            ->options(
                                Product::where('is_active', 1)
                                    ->where('is_stock', 1)
                                    ->where('quantity', '>', 0)
                                    ->pluck('name', 'id')
                            )
                            ->reactive()
                            ->afterStateUpdated(
                                function (Set $set, $state) {
                                    // $state ở đây là id của sản phẩm mình vừa chọn
                                    // Log::info($state);
                        
                                    $product = Product::find($state);

                                    if ($product) {
                                        $set('quantity', 1);
                                        $set('total_price', (float) $product->price);
                                    } else {
                                        $set('quantity', 0);
                                        $set('total_price', 0);
                                    }
                                }
                            ),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity')
                            ->required()
                            ->minValue(1)
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(
                                function (Set $set, Get $get, $state) {
                                    // $state ở đây là số lượng mình vừa nhập
                                    // Log::info($state);
                        
                                    $product = Product::find($get('product_id'));

                                    if ($state > $product->quantity) {
                                        $set('quantity', 1);
                                    }

                                    if ($product) {
                                        $set('total_price', (float) $product->price * (float) $get('quantity'));
                                    }
                                }
                            ),

                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Price')
                            ->formatStateUsing(fn($state) => number_format((float) $state, 2, '.', ''))
                            ->readOnly()
                    ]
                )->columns(3)
        ];
    }
}
