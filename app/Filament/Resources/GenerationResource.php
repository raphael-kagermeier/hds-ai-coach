<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenerationResource\Pages;
use App\Models\Generation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GenerationResource extends Resource
{
    protected static ?string $model = Generation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('input_text')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('lesson_id')
                    ->relationship('lesson', 'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('images')
                    ->disk('public')
                    ->multiple()
                    ->image()
                    ->required(),
                Forms\Components\Textarea::make('generated_text')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('final_text')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lesson.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
                Infolists\Components\TextEntry::make('lesson.name')
                    ->label('Lesson'),
                Infolists\Components\TextEntry::make('status'),
                Infolists\Components\TextEntry::make('input_text')
                    ->columnSpanFull(),
                Infolists\Components\ImageEntry::make('images')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('final_text')
                    ->html()
                    ->columnSpanFull(),
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
            'index' => Pages\ListGenerations::route('/'),
            'create' => Pages\CreateGeneration::route('/create'),
            'edit' => Pages\EditGeneration::route('/{record}/edit'),
            'generate' => Pages\Generate::route('/generate'),
            'review' => Pages\ReviewGeneration::route('/{record}/review'),
            'view' => Pages\ViewGeneration::route('/{record}'),
        ];
    }
}
