<?php

namespace App\Filament\Resources\GenerationResource\Pages;

use App\Filament\Resources\GenerationResource;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

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

                FileUpload::make('images')
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->required()
                    ->disabled()
                    ->columnSpanFull(),
            ])
            ->columns(null);
    }

    public function getTitle(): string|Htmlable
    {
        return 'Review Generation for '.$this->getRecord()->name;
    }

    public function updated(string $_): void
    {
        $this->save(shouldRedirect: false, shouldSendSavedNotification: false);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->hidden()
            ->color('gray');
    }
}
