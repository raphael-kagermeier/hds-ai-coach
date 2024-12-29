<?php

namespace App\Filament\Resources\GenerationResource\Pages;

use App\Filament\Resources\GenerationResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class Generate extends CreateRecord
{
    protected static string $resource = GenerationResource::class;

    protected static ?string $title = 'Generate Response';

    protected function getRedirectUrl(): string
    {
        return GenerationResource::getUrl('review', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        return $data;
    }

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                FileUpload::make('images')
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->required()
                    ->columnSpanFull(),
                Select::make('lesson_id')
                    ->relationship('lesson', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
            ])
            ->columns(null);
    }
}
