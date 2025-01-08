<?php

namespace App\Filament\Resources\GenerationResource\Pages;

use App\Filament\Resources\GenerationResource;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class ReviewGeneration extends EditRecord
{
    protected static string $resource = GenerationResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                RichEditor::make('final_text')
                    ->label('Final Text')
                    ->required()
                    ->live(debounce: 500)
                    ->disableGrammarly()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(null);
    }

    public function updated(string $_): void
    {
        $this->save(shouldRedirect: false, shouldSendSavedNotification: false);
    }
}
