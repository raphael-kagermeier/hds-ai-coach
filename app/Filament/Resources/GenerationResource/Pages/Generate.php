<?php

namespace App\Filament\Resources\GenerationResource\Pages;

use App\Filament\Resources\GenerationResource;
use App\Models\Generation;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Auth;
use function Filament\Support\is_app_url;

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
