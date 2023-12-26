<?php

namespace App\Filament\Resources;
use Filament\Resources\Resource;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CommentsRelationManager;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup= 'Users Management';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('Enter your name'),

                    TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->placeholder('Enter your email'),

                    TextInput::make('age')
                    ->label('Age')
                    ->required()
                    ->placeholder('Enter your age'),

               Select::make('gender')
                    ->label('Gender')
                    ->required()
                    ->placeholder('Select your gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ]),

                    TextInput::make('password')
                    ->label('Password')
                    ->required()
                    ->placeholder('Enter your password'),
                    Select::make('role')
                    ->options(function () {
                        // Get all roles
                        $roles = User::ROLES;
                
                        // Exclude the 'ADMIN' role
                        unset($roles[User::ROLE_ADMIN]);
                
                        // Return the filtered roles
                        return $roles;
                    })
                    ->required()
                

                // Add other fields as needed
            ])
            ->context('create', 'update', function (Form $form) {
                return $form->schema([
                    Forms\Alert::make('info')
                        ->message('All fields are required. Please fill them out.'),
                ]);
            });
    }

    public static function getNavigationBadge(): ?string{
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null{
        return 'warning';
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('role')
                ->badge()
                ->color(function ( string $state ) : string {

                    return match ($state) {
                        'ADMIN' => 'danger',
                        'EDITOR' => 'info',
                        'USER'=> 'success',

                };
            })



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
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
