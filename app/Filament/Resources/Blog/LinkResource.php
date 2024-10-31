<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\LinkResource\Pages;
use App\Filament\Resources\Blog\LinkResource\RelationManagers;
use App\Models\Blog\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;

class LinkResource extends Resource
{
    use Translatable;
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationLabel = 'Links';
    protected static ?string $modelLabel = 'Links';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $slug = 'links';
    protected static ?int $navigationSort = 4;

    // global search
    protected static ?string $recordTitleAttribute = 'title';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return 'Link';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Title' => $record->title
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema(
                        [
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(100),

                            Forms\Components\ColorPicker::make('color')
                                ->required()
                                ->hex()
                                ->hexColor()
                        ]
                    )->columns(2),

                Forms\Components\TextInput::make('url')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required()
                    ->directory('link-images')
                    ->columnSpanFull(),


                Forms\Components\MarkdownEditor::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image')
                        ->height('138px')
                        ->width('245px')
                        ->extraAttributes(
                            ['style' => 'object-fit: cover;']
                        ),

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->weight(FontWeight::Bold)
                            ->limit(30),

                        Tables\Columns\TextColumn::make('url')
                            ->color('gray')
                            ->limit(30),
                    ]),
                ])->space(3),

                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\ColorColumn::make('color')
                            ->grow(false),
                        Tables\Columns\TextColumn::make('description')
                            ->color('gray'),
                    ]),
                ])->collapsible(),
            ])
            ->filters([
                //
            ])
            // chia layout thành bao nhiêu phần, ở màn hình nào
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            // chọn số bản ghi hiển thị
            ->paginated([
                18,
                36,
                72,
                'all',
            ])
            ->actions([
                Tables\Actions\Action::make('visit')
                    ->label('Visit link')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn(Link $record) => $record->url),

                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }
}
