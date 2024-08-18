<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthSecretResource\Pages;
use App\Filament\Resources\AuthSecretResource\RelationManagers;
use App\Models\App;
use App\Models\AuthSecret;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthSecretResource extends Resource
{
    protected static ?string $model = AuthSecret::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make("app_id")
                    ->required()
                    ->options(App::pluck('name', 'id')),
                Forms\Components\TextInput::make('client_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_secret')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_access_token')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_refresh_token')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->disabled()
                    ->default(now()->addMonth())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('app.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_secret')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_access_token')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_refresh_token')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListAuthSecrets::route('/'),
            'create' => Pages\CreateAuthSecret::route('/create'),
            'edit' => Pages\EditAuthSecret::route('/{record}/edit'),
        ];
    }
}
