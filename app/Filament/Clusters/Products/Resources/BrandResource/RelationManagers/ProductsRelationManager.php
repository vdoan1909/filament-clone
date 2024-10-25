<?php

namespace App\Filament\Clusters\Products\Resources\BrandResource\RelationManagers;

use App\Models\Shop\Brand;
use App\Models\Shop\ShopCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(100),

                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU')
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(15)
                                            ->default(strtoupper(Str::random(8)))
                                            ->readOnly(),
                                    ])->columns(2),

                                Forms\Components\MarkdownEditor::make('description')
                                    ->columnSpan('full')
                            ]),

                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->required()
                            ->directory('productImages'),

                        Forms\Components\Section::make('Prices')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->required(),

                                Forms\Components\TextInput::make('old_price')
                                    ->label('Old Price')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->nullable()
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(0)
                                    ->rules(['integer', 'min:0']),

                                Forms\Components\TextInput::make('security_stock')
                                    ->label('Stock')
                                    ->numeric()
                                    ->rules(['integer', 'min:0'])
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->maxLength(100),

                                Forms\Components\MarkdownEditor::make('seo_description')
                                    ->label('SEO Description')
                                    ->columnSpan('full')
                            ]),

                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Relations')
                            ->schema([
                                Forms\Components\Select::make('shop_category_id')
                                    ->label('Category')
                                    ->options(
                                        fn() => ShopCategory::where('is_active', 1)
                                            ->pluck('name', 'id')
                                    )
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),

                                Forms\Components\Select::make('brand_id')
                                    ->label('Brand')
                                    ->options(
                                        fn() => Brand::where('is_active', 1)
                                            ->pluck('name', 'id')
                                    )
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                            ]),

                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->helperText('This product will be hidden from all sales channels.')
                                    ->default(false),

                                Forms\Components\Toggle::make('is_stock')
                                    ->label('Stock Status')
                                    ->helperText('Is this product still in stock?')
                                    ->default(true),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Published At')
                                    ->default(now())
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand'),

                Tables\Columns\TextColumn::make('shop_category.name')
                    ->label('Category')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('old_price')
                    ->label('Old Price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                Tables\Columns\TextColumn::make('security_stock')
                    ->label('Stock')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published At')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('seo_title')
                    ->label('SEO Title')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('seo_description')
                    ->label('SEO Description')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active Status')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_stock')
                    ->label('Stock Status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created At')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->options(
                        fn() => Brand::where('is_active', 1)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload(),

                \Filament\Tables\Filters\SelectFilter::make('shop_category')
                    ->relationship('shop_category', 'name')
                    ->options(
                        fn() => ShopCategory::where('is_active', 1)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload(),

                \Filament\Tables\Filters\SelectFilter::make('is_active')
                    ->label('Active Status')
                    ->native(false)
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),

                \Filament\Tables\Filters\SelectFilter::make('is_stock')
                    ->label('Stock Status')
                    ->native(false)
                    ->options([
                        1 => 'In Stock',
                        0 => 'Out Of Stock',
                    ]),

                QueryBuilder::make()
                    ->constraints([
                        NumberConstraint::make('old_price')
                            ->label('Old price')
                            ->icon('heroicon-m-currency-dollar'),
                        NumberConstraint::make('price')
                            ->icon('heroicon-m-currency-dollar'),
                        NumberConstraint::make('quantity')
                            ->label('Quantity'),
                        NumberConstraint::make('security_stock')
                            ->label('Stock'),
                        DateConstraint::make('published_at')
                            ->label('Published At'),
                    ])
                    ->constraintPickerColumns(2),
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => !$record->trashed()),

                Tables\Actions\DeleteAction::make()
                    ->label('Soft Delete')
                    ->visible(fn($record) => !$record->trashed())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Soft Delete')
                            ->body('Soft Delete successfully')
                    ),

                Tables\Actions\RestoreAction::make()
                    ->label('Restore')
                    ->visible(fn($record) => $record->trashed())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Restore')
                            ->body('Restore successfully')
                    ),

                Tables\Actions\ForceDeleteAction::make()
                    ->label('Force Delete')
                    ->visible(fn($record) => $record->trashed())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Force Delete')
                            ->body('Force Delete successfully')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
