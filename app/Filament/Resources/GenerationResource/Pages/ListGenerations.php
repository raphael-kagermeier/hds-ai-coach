<?php

namespace App\Filament\Resources\GenerationResource\Pages;

use App\Filament\Resources\GenerationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGenerations extends ListRecords
{
    protected static string $resource = GenerationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->color('gray'),
            Actions\Action::make('generate')
                    ->url(GenerationResource::getUrl('generate'))
                    ->label('Generate')
                    ->icon('heroicon-o-play'),
        ];
    }
}
