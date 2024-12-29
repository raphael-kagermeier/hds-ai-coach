<?php

namespace App\Filament\Resources\GenerationResource\Pages;

use App\Filament\Resources\GenerationResource;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class ReviewGeneration extends EditRecord
{
    protected static string $resource = GenerationResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                MarkdownEditor::make('final_text')
                    ->label('Final Text')
                    ->required()
                    ->live(debounce: 500)
                    ->columnSpanFull(),
            ])
            ->columns(null);
    }

    public function updated(string $_): void
    {
        $this->save(shouldRedirect: false, shouldSendSavedNotification: false);
    }
}
