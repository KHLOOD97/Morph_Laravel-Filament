<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Post;
use App\Models\Comments; // Make sure this use statement is correct

use App\Filament\Resources\CommentsResource\Pages;
use App\Filament\Resources\CommentsResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MorphToSelect;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;

class CommentsResource extends Resource
{
    protected static ?string $model = Comments::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup= 'Comments Section';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')->relationship('user', 'name')->searchable()->preload(),
                TextInput::make('comment'),
                MorphToSelect::make('commentable')
                    ->label('Comment Type')
                    ->types([
                        MorphToSelect\Type::make(Post::class)->titleAttribute('title'),
                        MorphToSelect\Type::make(User::class)->titleAttribute('email'),
                        MorphToSelect\Type::make(Comments::class)->titleAttribute('id'),
                    ])
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('comment'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    public static function getNavigationBadge(): ?string{
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null{
        return 'warning';
    }
    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComments::route('/create'),
            'edit' => Pages\EditComments::route('/{record}/edit'),
        ];
    }
}
