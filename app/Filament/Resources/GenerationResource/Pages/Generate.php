<?php

namespace App\Filament\Resources\GenerationResource\Pages;

use App\Filament\Resources\GenerationResource;
use App\Models\Generation;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class Generate extends CreateRecord
{
    protected static string $resource = GenerationResource::class;

    use CreateRecord\Concerns\HasWizard;

    public ?Generation $generatedRecord = null;

    protected function getSteps(): array
    {
        return [
            Step::make('Images & Lesson')
                ->description('Upload images and select a lesson')
                ->schema([
                    Section::make()
                        ->schema([
                            FileUpload::make('images')
                                ->multiple()
                                ->image()
                                ->required()
                                ->columnSpanFull(),
                            Select::make('lesson_id')
                                ->relationship('lesson', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpanFull(),
                        ]),
                ])
                ->afterValidation(function ($get) {
                    $data = $get();
                    $data['user_id'] = Auth::id();
                    $data['status'] = 'pending';
                    
                    $this->generatedRecord = Generation::create($data);
                }),

            Step::make('Generated Text')
                ->description('Review and edit generated text')
                ->schema([
                    Section::make()
                        ->schema([
                            Textarea::make('generated_text')
                                ->label('Generated Text')
                                ->disabled()
                                ->dehydrated()
                                ->columnSpanFull(),
                            Textarea::make('final_text')
                                ->label('Final Text')
                                ->required()
                                ->columnSpanFull(),
                        ]),
                ]),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // We've already created the record after first step
        return $data;
    }
}
