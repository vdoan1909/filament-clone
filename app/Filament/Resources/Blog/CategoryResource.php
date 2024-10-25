<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\CategoryResource\Pages;
use App\Filament\Resources\Blog\CategoryResource\RelationManagers;
use App\Models\Blog\BlogCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = BlogCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $modelLabel = 'Category';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $slug = 'blog-categories';
    protected static ?int $navigationSort = 2;

    // global search
    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return 'Blog Category';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Blog Category Name' => $record->name
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Infomation')
                    ->schema(
                        [
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(100),
                            Forms\Components\MarkdownEditor::make('description')
                                ->nullable(),
                        ]
                    ),

                Forms\Components\Section::make('SEO')
                    ->schema(
                        [
                            Forms\Components\TextInput::make('seo_title')
                                ->label('SEO Title')
                                ->nullable()
                                ->maxLength(100),
                            Forms\Components\MarkdownEditor::make('seo_description')
                                ->label('SEO Description')
                                ->nullable(),
                        ]
                    ),

                Forms\Components\Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description'),

                Tables\Columns\TextColumn::make('seo_title')
                    ->label('SEO Title'),
                Tables\Columns\TextColumn::make('seo_description')
                    ->label('SEO Description')

                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active Status')
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
                \Filament\Tables\Filters\SelectFilter::make('is_active')
                    ->label('Filter By Status')
                    ->native(false)
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
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
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Soft Delete'),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Restore'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            // 'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
            'trash' => Pages\TrashCategories::route('/trash'),
        ];
    }
}
