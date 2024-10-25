<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\PostResource\Pages;
use App\Filament\Resources\Blog\PostResource\RelationManagers;
use App\Models\Blog\BlogAuthor;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\Post;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Components;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $modelLabel = 'Posts';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $slug = 'posts';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGlobalSearchAttributes(): array
    {
        return [
            'title'
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Author' => $record->blog_author->name,
            'Category' => $record->blog_category->name,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(
                                [
                                    Forms\Components\TextInput::make('title')
                                        ->required()
                                        ->maxLength(100),

                                    Forms\Components\MarkdownEditor::make('content')
                                        ->required()
                                        ->columnSpanFull(),
                                ]
                            ),

                        Forms\Components\Section::make()
                            ->schema(
                                [
                                    Forms\Components\Select::make('blog_author_id')
                                        ->label('Author')
                                        ->native(false)
                                        ->required()
                                        ->options(
                                            fn() => BlogAuthor::pluck('name', 'id')
                                        )
                                        ->searchable()
                                        ->preload(),

                                    Forms\Components\Select::make('blog_category_id')
                                        ->label('Category')
                                        ->native(false)
                                        ->required()
                                        ->options(
                                            fn() => BlogCategory::where('is_active', 1)
                                                ->pluck('name', 'id')
                                        )
                                        ->searchable()
                                        ->preload()
                                ]
                            )->columns(2),


                        Forms\Components\Section::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->required()
                                    ->columnSpanFull()
                            ])

                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Published At')
                                    ->required()
                                    ->native(false)
                                    ->default(now()),

                                SpatieTagsInput::make('tags')
                                    ->type('product')
                                    ->label('Tags')
                                    ->placeholder('Add product tags'),
                            ])->columns(2),

                        Forms\Components\FileUpload::make('image')
                            ->directory('post-images')
                            ->nullable()
                            ->columnSpanFull()
                    ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('blog_author.name')
                    ->label('Author'),
                Tables\Columns\TextColumn::make('blog_category.name')
                    ->label('Category')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn(Post $record): string => $record->published_at ? 'Published' : 'Draft')
                    ->colors([
                        'success' => 'Published',
                    ]),

                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable()
                    ->label('Published Date'),
                Tables\Columns\TextColumn::make('seo_title')
                    ->searchable(),
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
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')
                            ->native(false)
                            ->label('Published From'),
                        Forms\Components\DatePicker::make('published_until')
                            ->native(false)
                            ->label('Published Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['published_from'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make('Published from ' . Carbon::parse($data['published_from'])->toFormattedDateString())
                                ->removeField('published_from');
                        }

                        if ($data['published_until'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make('Published until ' . Carbon::parse($data['published_until'])->toFormattedDateString())
                                ->removeField('published_until');
                        }

                        return $indicators;
                    })
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\Split::make([
                            Components\Grid::make(2)
                                ->schema([
                                    Components\Group::make([
                                        Components\TextEntry::make('title'),
                                        Components\TextEntry::make('slug'),
                                        Components\TextEntry::make('published_at')
                                            ->badge()
                                            ->date()
                                            ->color('success'),
                                    ]),

                                    Components\Group::make([
                                        Components\TextEntry::make('blog_author.name')
                                            ->label('Author'),
                                        Components\TextEntry::make('blog_category.name')
                                            ->label('Category'),
                                        Components\SpatieTagsEntry::make('tags'),
                                    ]),
                                ]),
                            Components\ImageEntry::make('image')
                                ->hiddenLabel()
                        ]),
                    ]),

                Components\Section::make('Content')
                    ->schema([
                        Components\TextEntry::make('content')
                            ->markdown()
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPost::class,
            Pages\EditPost::class
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
