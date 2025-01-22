<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('Product Name'),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Product Description'),
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->placeholder('Product Price'),
                Forms\Components\TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->placeholder('Product Stock'),
                Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\Select::make('category_id')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->options(fn () => Category::all()->pluck('name', 'id'))
                    ->multiple()
                    ->placeholder('Select a Category'),
                Forms\Components\Select::make('brand_id')
                    ->label('Brands')
                    ->relationship('brands', 'name')
                    ->options(fn () => Brand::all()->pluck('name', 'id'))
                    ->multiple()
                    ->placeholder('Select a Brand'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('stock')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->label('Categories')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('brands.name')
                    ->label('Brands')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/y H:i')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->sortable()
                    ->dateTime()
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/y H:i')),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
