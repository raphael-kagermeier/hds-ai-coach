<?php

namespace App\Filament\Resources\GenerationResource\Pages;

use App\Filament\Resources\GenerationResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class Generate extends CreateRecord
{
    protected static string $resource = GenerationResource::class;

    protected static ?string $title = 'Generate Response';

    protected static bool $canCreateAnother = false;

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
                Fieldset::make('')
                    ->schema([
                        TextInput::make('name')
                            ->label('Talent Name')
                            ->required(),
                        Select::make('lesson_id')
                            ->label('Select the lesson')
                            ->relationship(
                                name: 'lesson',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->with('course')
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->course->name}: {$record->name}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        FileUpload::make('images')
                            ->disk('s3_public')
                            ->multiple()
                            ->image()
                            ->required()
                            ->columnSpanFull(),
                    ]),

            ])
            ->columns(null);
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label(__('Generate response (⌘G)'))
            ->submit('create')
            ->icon('heroicon-o-arrow-right')
            ->iconPosition('after')
            ->keyBindings(['mod+g']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->hidden()
            ->color('gray');
    }
}
