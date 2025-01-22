<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('John Doe'),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->placeholder('john@doe.com'),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->required()
                    ->placeholder('********')
                    ->password()
                    ->autocomplete('new-password'),
                Forms\Components\Toggle::make('active')
                    ->label('Ativo')
                    ->default(true),
                Forms\Components\Toggle::make('is_admin')
                    ->label('Admin')
                    ->default(false)
                    ->visible(fn (?Model $record) => $record?->id !== Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Ativo')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_admin')
                    ->label('Admin')
                    ->sortable()
                    ->disabled(fn (?Model $record) => $record?->id === Auth::id()),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('d/m/y H:i')),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (true) {
                    return $query->where('is_super', false);
                }
            })
            ->filters([
                Tables\Filters\Filter::make('Exclude Super Users')
                    ->query(fn (Builder $query) => $query->where('is_super', false))
                    ->default(), // Applies by default
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])

                ,
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

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

}
