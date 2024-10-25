<?php

namespace App\Filament\Resources\Blog\AuthorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Blog\BlogAuthor;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\Post;
use Filament\Forms\Components\SpatieTagsInput;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
